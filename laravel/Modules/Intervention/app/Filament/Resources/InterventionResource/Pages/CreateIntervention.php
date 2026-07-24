<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\Pages;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Filament\Concerns\ResolvesCalendarReturnUrl;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateIntervention extends XotBaseCreateRecord
{
    use ResolvesCalendarReturnUrl;

    protected static string $resource = InterventionResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        $user = Auth::user();
        $panelId = filament()->getCurrentPanel()?->getId();

        if ($panelId === 'operator' || request()->is('operator/*')) {
            if (! $user instanceof User || ! $user->can('Create:Intervention')) {
                return false;
            }

            $fromCalendar = request()->filled('scheduled_at') && request()->filled('return_to');

            if ($fromCalendar) {
                return AccountFeatures::hasFeature($user, 'operator.create_intervention_from_calendar', false);
            }

            return AccountFeatures::hasFeature($user, 'operator.create_intervention_from_list', true);
        }

        return parent::canAccess($parameters);
    }

    public function mount(): void
    {
        parent::mount();

        $scheduledAt = request()->query('scheduled_at');
        $workOrderId = (int) request()->query('work_order_id', '0');
        $customerId = (int) request()->query('customer_id', '0');

        $defaults = [];

        $directCustomerMode = InterventionResource::isDirectCustomerMode();

        if (is_string($scheduledAt) && trim($scheduledAt) !== '') {
            $parsedScheduledAt = CarbonImmutable::parse($scheduledAt);
            if ($directCustomerMode) {
                $defaults['scheduled_date'] = $parsedScheduledAt->toDateString();
            } else {
                $defaults['scheduled_at'] = $parsedScheduledAt->format('Y-m-d H:i:s');
            }
        }

        if ($workOrderId > 0) {
            $defaults['work_order_id'] = $workOrderId;
        }

        if ($customerId > 0 && $directCustomerMode) {
            $defaults['customer_id'] = $customerId;
        }

        if ($this->shouldForceSelfAssignment()) {
            $defaults['performed_by_user_id'] = Auth::id();
        }

        if ($defaults !== []) {
            $this->form->fill($defaults);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = InterventionResource::normalizeFormData($data);

        if ($this->shouldForceSelfAssignment()) {
            $data['performed_by_user_id'] = Auth::id();
        }

        return InterventionResource::extractPersistableData($data);
    }

    protected function afterCreate(): void
    {
        if ($this->record instanceof Intervention) {
            InterventionResource::syncSupplementalData($this->record, is_array($this->data) ? $this->data : []);
        }
    }

    protected function getRedirectUrl(): string
    {
        $returnUrl = $this->getRequestedReturnUrl();
        if ($returnUrl !== null) {
            return $returnUrl;
        }

        if ($this->shouldForceSelfAssignment() && $this->record instanceof Intervention) {
            return InterventionResource::getUrl('view', ['record' => $this->record]);
        }

        return parent::getRedirectUrl();
    }

    private function shouldForceSelfAssignment(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && AccountFeatures::hasAssignedPerimeter($user)
            && AccountFeatures::hasAccountFeature($user, 'operator.force_self_assignment')
            && request()->is('operator/*');
    }
}
