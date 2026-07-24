<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Actions\SearchCompanyOnlineAction;
use Modules\Customer\Lookup\CompanyLookupResult;
use Modules\Quotation\Actions\ApplyTechnicalParamsToQuotationItemsAction;
use Modules\Quotation\Actions\BuildQuotationDraftAction;
use Modules\Quotation\Actions\BuildQuotationModificationAction;
use Modules\Quotation\Actions\CreateQuotationFromDraftAction;
use Modules\Quotation\Actions\DuplicateQuotationAsDraftAction;
use Modules\Quotation\Actions\QueueQuotationAttachmentRewriteAction;
use Modules\Quotation\Actions\ResolveOrCreateCustomerFromDraftAction;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\DTO\QuotationCustomerDraft;
use Modules\Quotation\DTO\QuotationDraft;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Quotation\Models\Quotation;
use Modules\User\Models\User;
use Modules\Xot\Filament\Pages\XotBasePage;

/**
 * Mobile-first page: the plumber dictates a quotation by voice; the AI turns it
 * into a draft (customer + catalog-matched line items), optionally seeded from an
 * existing quotation ("create a similar one for another customer"). The operator
 * reviews and confirms, creating a brand-new quotation.
 */
final class RecordQuotationVoicePage extends XotBasePage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $slug = 'voice-quotation';

    protected string $view = 'quotations::filament.pages.record-quotation-voice';

    public ?int $sourceId = null;

    public string $sourceLabel = '';

    public string $transcript = '';

    /** @var array<string, mixed> Raw AI extraction of the dictation, for transparency. */
    public array $voiceAiResponse = [];

    public bool $hasDraft = false;

    public string $customerLegalName = '';

    public string $customerVat = '';

    public string $customerEmail = '';

    public string $customerPhone = '';

    public string $customerCity = '';

    public string $customerAddress = '';

    public string $customerPostalCode = '';

    public string $customerProvince = '';

    public string $customerTaxCode = '';

    public string $companyQuery = '';

    /** @var list<array{legal_name: string, vat_number: string|null, tax_code: string|null, address_line1: string|null, city: string|null, postal_code: string|null, province: string|null, phone: string|null, email: string|null}> */
    public array $companyCandidates = [];

    public string $notes = '';

    public ?float $taxPercent = null;

    /** @var array<string, mixed> Structured technical survey parameters keyed by param key. */
    public array $technicalParams = [];

    public string $quotationTitle = '';

    /** @var array<int, array{description: string, quantity: float, unit_price: float|null, matched_product_id: int|null, is_matched: bool}> */
    public array $items = [];

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        $panelId = filament()->getCurrentPanel()?->getId();

        return match ($panelId) {
            'operator' => AccountFeatures::hasFeature($user, 'operator.voice_quotation', true),
            'admin' => AccountFeatures::hasFeature($user, 'admin.voice_quotation', true),
            default => false,
        };
    }

    public function getTitle(): string
    {
        return __('quotations.voice.title');
    }

    public function getHeading(): string
    {
        return __('quotations.voice.title');
    }

    public function mount(): void
    {
        $sourceParam = request()->query('source');
        if (! is_numeric($sourceParam)) {
            return;
        }

        $source = Quotation::query()->find((int) $sourceParam);
        if ($source === null) {
            return;
        }

        $draft = app(DuplicateQuotationAsDraftAction::class)->execute($source);
        $this->applyDraft($draft, replaceItems: true);
        $this->sourceId = (int) $source->id;
        $this->sourceLabel = (string) ($source->title ?: ('#'.$source->id));
        $this->hasDraft = true;
    }

    public function generateDraft(): void
    {
        $transcript = trim($this->transcript);
        if ($transcript === '') {
            Notification::make()->title(__('quotations.voice.empty_transcript'))->warning()->send();

            return;
        }

        if ($this->items !== []) {
            // Copy-then-modify: the AI recognizes the existing quotation structure
            // and edits only what's requested, keeping the other lines verbatim.
            $modified = app(BuildQuotationModificationAction::class)->execute(
                $transcript,
                $this->itemsFromState(),
                $this->customerFromState(),
            );
            $this->applyModification($modified);
        } else {
            // New quotation from scratch: extract everything from the voice note.
            $draft = app(BuildQuotationDraftAction::class)->execute($transcript);
            if ($draft->basedOnQuotationId !== null) {
                $this->sourceId = $draft->basedOnQuotationId;
            }
            $this->applyDraft($draft, replaceItems: false);
            $this->voiceAiResponse = $draft->aiResponse ?? [];
            // Auto-enrich the company from its name (typo-tolerant), no click needed.
            $this->autofillCompanyFromName();
        }

        $this->hasDraft = true;
    }

    private function copySourceAttachments(Quotation $quotation): void
    {
        if ($this->sourceId === null || $this->sourceId <= 0) {
            return;
        }

        $source = Quotation::query()->find($this->sourceId);
        if ($source === null || (int) $source->id === (int) $quotation->id) {
            return;
        }

        // Remember the source so the operator can re-prepare the attachment later.
        $quotation->source_quotation_id = (int) $source->id;
        $quotation->save();

        // The AI work (rewrite the attachment; recompute notes' dates/payments on the
        // new total) is slow and may retry with a stronger model — run it on the queue
        // so confirm never blocks or times out. When there's a document, a placeholder
        // row shows in the attachments table until the job swaps it for the real file.
        $userId = Auth::id();
        app(QueueQuotationAttachmentRewriteAction::class)->execute(
            $quotation,
            trim($this->customerLegalName),
            is_numeric($userId) ? (int) $userId : null,
        );
    }

    private function applyModification(QuotationDraft $draft): void
    {
        if ($draft->customer !== null) {
            if ($draft->customer->legalName !== '') {
                $this->customerLegalName = $draft->customer->legalName;
            }
            $this->customerVat = $draft->customer->vatNumber ?? $this->customerVat;
            $this->customerEmail = $draft->customer->email ?? $this->customerEmail;
            $this->customerPhone = $draft->customer->phone ?? $this->customerPhone;
            $this->customerCity = $draft->customer->city ?? $this->customerCity;
        }

        $this->items = $this->itemsToState($draft->items);
    }

    /**
     * Enriches the customer from its (possibly mistyped) name during voice creation,
     * without the operator clicking "search online". Corrects the name to the
     * canonical one found and fills the still-empty fields; leaves dictated data.
     */
    private function autofillCompanyFromName(): void
    {
        $name = trim($this->customerLegalName);
        if ($name === '') {
            return;
        }

        // Skip when we already have solid registry data (avoid needless lookups).
        if (trim($this->customerVat) !== '' && trim($this->customerAddress) !== '') {
            return;
        }

        $query = trim($this->customerVat) !== '' ? $name.' '.trim($this->customerVat) : $name;
        $results = app(SearchCompanyOnlineAction::class)->execute($query);
        if ($results === []) {
            return;
        }

        $company = $results[0];
        if ($company->legalName !== '') {
            $this->customerLegalName = $company->legalName; // correct the typed/dictated name
        }
        $this->customerVat = $this->fillIfEmpty($this->customerVat, $company->vatNumber);
        $this->customerTaxCode = $this->fillIfEmpty($this->customerTaxCode, $company->taxCode);
        $this->customerAddress = $this->fillIfEmpty($this->customerAddress, $company->addressLine1);
        $this->customerCity = $this->fillIfEmpty($this->customerCity, $company->city);
        $this->customerPostalCode = $this->fillIfEmpty($this->customerPostalCode, $company->postalCode);
        $this->customerProvince = $this->fillIfEmpty($this->customerProvince, $company->province);
        $this->customerPhone = $this->fillIfEmpty($this->customerPhone, $company->phone);
        $this->customerEmail = $this->fillIfEmpty($this->customerEmail, $company->email);
    }

    private function fillIfEmpty(string $current, ?string $value): string
    {
        return trim($current) !== '' ? $current : (string) ($value ?? '');
    }

    /**
     * Options for the "based on which quotation" dropdown: a "none" entry plus the
     * most recent quotations (label = title — customer). The currently detected
     * source is always included so the operator can see/change/clear it.
     *
     * @return array<int, string>
     */
    public function sourceOptions(): array
    {
        $options = [0 => __('quotations.voice.based_on_none')];

        $ids = DB::table('quotations')
            ->leftJoin('customers', 'customers.id', '=', 'quotations.customer_id')
            ->orderByDesc('quotations.id')
            ->limit(50)
            ->get(['quotations.id', 'quotations.title', 'customers.legal_name']);

        foreach ($ids as $row) {
            $options[(int) $row->id] = $this->sourceLabelFor($row->id, $row->title, $row->legal_name);
        }

        // Keep the detected source visible even if it fell out of the recent list.
        if ($this->sourceId !== null && $this->sourceId > 0 && ! isset($options[$this->sourceId])) {
            $source = DB::table('quotations')
                ->leftJoin('customers', 'customers.id', '=', 'quotations.customer_id')
                ->where('quotations.id', $this->sourceId)
                ->first(['quotations.id', 'quotations.title', 'customers.legal_name']);
            if ($source !== null) {
                $options[(int) $source->id] = $this->sourceLabelFor($source->id, $source->title, $source->legal_name);
            }
        }

        return $options;
    }

    private function sourceLabelFor(mixed $id, mixed $title, mixed $legalName): string
    {
        $idLabel = is_string($title) && trim($title) !== '' ? trim($title) : '#'.(string) $id;
        $legalNameLabel = is_string($legalName) && trim($legalName) !== '' ? trim($legalName) : '';

        return $legalNameLabel !== '' ? $idLabel.' — '.$legalNameLabel : $idLabel;
    }

    public function searchCompany(): void
    {
        $query = trim($this->companyQuery);
        // Fallback: let the operator re-search from the name (+ VAT) they typed by hand.
        if ($query === '') {
            $query = trim($this->customerLegalName.' '.$this->customerVat);
        }
        if ($query === '') {
            $this->companyCandidates = [];

            return;
        }

        $results = app(SearchCompanyOnlineAction::class)->execute($query);
        $this->companyCandidates = array_map(
            static fn (CompanyLookupResult $result): array => [
                'legal_name' => $result->legalName,
                'vat_number' => $result->vatNumber,
                'tax_code' => $result->taxCode,
                'address_line1' => $result->addressLine1,
                'city' => $result->city,
                'postal_code' => $result->postalCode,
                'province' => $result->province,
                'phone' => $result->phone,
                'email' => $result->email,
            ],
            $results,
        );

        if ($this->companyCandidates === []) {
            Notification::make()->title(__('quotations.voice.no_company_found'))->warning()->send();
        }

        $this->hasDraft = true;
    }

    public function pickCompany(int $index): void
    {
        $candidate = $this->companyCandidates[$index] ?? null;
        if ($candidate === null) {
            return;
        }

        $this->customerLegalName = $candidate['legal_name'];
        $this->customerVat = $candidate['vat_number'] ?? '';
        $this->customerCity = $candidate['city'] ?? '';
        $this->customerEmail = $candidate['email'] ?? '';
        $this->customerPhone = $candidate['phone'] ?? '';
        $this->customerAddress = $candidate['address_line1'] ?? '';
        $this->customerPostalCode = $candidate['postal_code'] ?? '';
        $this->customerProvince = $candidate['province'] ?? '';
        $this->customerTaxCode = $candidate['tax_code'] ?? '';
        $this->companyCandidates = [];
    }

    public function addItemRow(): void
    {
        $this->items[] = [
            'description' => '',
            'quantity' => 1.0,
            'unit_price' => null,
            'matched_product_id' => null,
            'is_matched' => false,
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function confirm(): void
    {
        $customer = $this->customerFromState();
        if (! $customer->hasIdentity()) {
            Notification::make()->title(__('quotations.voice.customer_required'))->warning()->send();

            return;
        }

        $items = $this->itemsFromState();
        if ($items === []) {
            Notification::make()->title(__('quotations.voice.items_required'))->warning()->send();

            return;
        }

        $draft = new QuotationDraft(
            customer: $customer,
            title: trim($this->quotationTitle) !== '' ? trim($this->quotationTitle) : null,
            notes: trim($this->notes) !== '' ? trim($this->notes) : null,
            items: $items,
            taxPercent: $this->taxPercent,
            technicalParams: $this->technicalParams,
        );

        $customerId = app(ResolveOrCreateCustomerFromDraftAction::class)->execute($customer);
        $quotation = app(CreateQuotationFromDraftAction::class)->execute($draft, $customerId);

        // Keep what was dictated + the raw AI extraction, for transparency/review.
        $transcript = trim($this->transcript);
        if ($transcript !== '' || $this->voiceAiResponse !== []) {
            $quotation->voice_transcript = $transcript !== '' ? $transcript : null;
            $quotation->voice_ai_response = $this->voiceAiResponse !== [] ? $this->voiceAiResponse : null;
            $quotation->save();
        }

        // When "like customer X's quotation", also give the new quotation an
        // attachment like the source one, but with the customer substituted.
        $this->copySourceAttachments($quotation);

        Notification::make()->title(__('quotations.voice.created'))->success()->send();

        $this->redirect(QuotationResource::getUrl('view', ['record' => $quotation->id]));
    }

    private function applyDraft(QuotationDraft $draft, bool $replaceItems): void
    {
        if ($draft->customer !== null) {
            $this->customerLegalName = $draft->customer->legalName !== '' ? $draft->customer->legalName : $this->customerLegalName;
            $this->customerVat = $draft->customer->vatNumber ?? $this->customerVat;
            $this->customerTaxCode = $draft->customer->taxCode ?? $this->customerTaxCode;
            $this->customerEmail = $draft->customer->email ?? $this->customerEmail;
            $this->customerPhone = $draft->customer->phone ?? $this->customerPhone;
            $this->customerAddress = $draft->customer->addressLine1 ?? $this->customerAddress;
            $this->customerCity = $draft->customer->city ?? $this->customerCity;
            $this->customerPostalCode = $draft->customer->postalCode ?? $this->customerPostalCode;
            $this->customerProvince = $draft->customer->province ?? $this->customerProvince;
        }

        if ($draft->title !== null) {
            $this->quotationTitle = $draft->title;
        }
        if ($draft->notes !== null) {
            $this->notes = $draft->notes;
        }
        if ($draft->taxPercent !== null) {
            $this->taxPercent = $draft->taxPercent;
        }

        $mapped = $this->itemsToState($draft->items);
        $this->items = $replaceItems ? $mapped : [...$this->items, ...$mapped];

        foreach ($draft->technicalParams as $key => $value) {
            $this->technicalParams[$key] = $value;
        }

        $this->recomputeItemsFromParams();
    }

    /**
     * Recomputes the rule-governed lines from the current technical parameters and
     * the current catalog prices, preserving the manually entered lines.
     */
    public function recalcFromParams(): void
    {
        $this->recomputeItemsFromParams();

        Notification::make()->title(__('quotations.voice.recalculated'))->success()->send();
    }

    /**
     * Technical parameter definitions (key/type/unit) from config, for the view.
     *
     * @return list<array{key: string, type: string, unit: string|null}>
     */
    public function technicalParamDefinitions(): array
    {
        /** @var list<array<string, mixed>> $definitions */
        $definitions = array_values(array_filter((array) config('quotation_technical.params', []), 'is_array'));

        $out = [];
        foreach ($definitions as $definition) {
            $key = is_string($definition['key'] ?? null) ? $definition['key'] : '';
            if ($key === '') {
                continue;
            }

            $out[] = [
                'key' => $key,
                'type' => is_string($definition['type'] ?? null) ? $definition['type'] : 'text',
                'unit' => is_string($definition['unit'] ?? null) ? $definition['unit'] : null,
            ];
        }

        return $out;
    }

    private function recomputeItemsFromParams(): void
    {
        $recomputed = app(ApplyTechnicalParamsToQuotationItemsAction::class)
            ->execute($this->itemsFromState(), $this->technicalParams);

        $this->items = $this->itemsToState($recomputed);
    }

    /**
     * @param  list<DictatedQuotationItem>  $items
     * @return list<array{description: string, quantity: float, unit_price: float|null, matched_product_id: int|null, is_matched: bool}>
     */
    private function itemsToState(array $items): array
    {
        return array_map(
            static fn (DictatedQuotationItem $item): array => [
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unitPrice,
                'matched_product_id' => $item->matchedProductId,
                'is_matched' => $item->isMatched(),
            ],
            $items,
        );
    }

    private function customerFromState(): QuotationCustomerDraft
    {
        return new QuotationCustomerDraft(
            legalName: trim($this->customerLegalName),
            vatNumber: $this->nullableTrim($this->customerVat),
            taxCode: $this->nullableTrim($this->customerTaxCode),
            email: $this->nullableTrim($this->customerEmail),
            phone: $this->nullableTrim($this->customerPhone),
            addressLine1: $this->nullableTrim($this->customerAddress),
            city: $this->nullableTrim($this->customerCity),
            postalCode: $this->nullableTrim($this->customerPostalCode),
            province: $this->nullableTrim($this->customerProvince),
        );
    }

    /**
     * @return list<DictatedQuotationItem>
     */
    private function itemsFromState(): array
    {
        $lines = [];
        foreach ($this->items as $item) {
            $description = trim((string) ($item['description'] ?? ''));
            if ($description === '') {
                continue;
            }

            $quantity = (float) ($item['quantity'] ?? 0);
            if ($quantity <= 0) {
                $quantity = 1.0;
            }

            $rawPrice = $item['unit_price'] ?? null;
            $rawProductId = $item['matched_product_id'] ?? null;

            $lines[] = new DictatedQuotationItem(
                description: $description,
                quantity: $quantity,
                unitPrice: is_numeric($rawPrice) ? (float) $rawPrice : null,
                matchedProductId: is_numeric($rawProductId) ? (int) $rawProductId : null,
            );
        }

        return $lines;
    }

    private function nullableTrim(string $value): ?string
    {
        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
