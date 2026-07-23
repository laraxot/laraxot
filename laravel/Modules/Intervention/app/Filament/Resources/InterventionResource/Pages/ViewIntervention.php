<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\Pages;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Modules\AiAssistant\Filament\Pages\AiAssistantChat;
use Modules\Intervention\Actions\CreateInterventionReportShareLinkAction;
use Modules\Intervention\Actions\GenerateInterventionReportPdfAction;
use Modules\Intervention\Filament\Concerns\ResolvesCalendarReturnUrl;
use Modules\Intervention\Filament\Pages\RecordInterventionReportPage;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Filament\Pages\CaptureSignature;
use Modules\Signature\Filament\Pages\CaptureSketch;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ViewIntervention extends ViewRecord
{
    use ResolvesCalendarReturnUrl;

    protected static string $resource = InterventionResource::class;

    protected function getHeaderActions(): array
    {
        /** @var int $interventionId */
        $interventionId = (int) $this->getRecord()->getKey();
        $returnUrl = $this->getRequestedReturnUrl();

        return [
            Action::make('voiceReport')
                ->label(__('interventions.voice_report.title'))
                ->icon('heroicon-o-microphone')
                ->color('primary')
                ->visible(fn (): bool => RecordInterventionReportPage::canAccess())
                ->url(fn (): string => RecordInterventionReportPage::getUrl(['intervention' => $interventionId])),
            Action::make('backToCalendar')
                ->label('Torna al calendario')
                ->icon('heroicon-o-arrow-left')
                ->visible(fn (): bool => $returnUrl !== null)
                ->url(fn (): ?string => $returnUrl),
            Action::make('editIntervention')
                ->label('Modifica')
                ->icon('heroicon-o-pencil-square')
                ->url(fn (): string => InterventionResource::getUrl('edit', [
                    'record' => $interventionId,
                    'return_to' => $returnUrl,
                ])),
            Action::make('openAiAssistant')
                ->label('Assistente AI')
                ->icon('heroicon-o-microphone')
                ->visible(fn (): bool => request()->is('operator/*')
                    && AccountFeatures::hasFeature(Auth::user(), 'operator.ai_assistant'))
                ->url(fn (): string => AiAssistantChat::getUrl([
                    'intervention_id' => $interventionId,
                ]))
                ->openUrlInNewTab(),
            DeleteAction::make()
                ->successRedirectUrl(fn (): ?string => $returnUrl),
            Action::make('captureSignature')
                ->label('Firma')
                ->icon('heroicon-o-pencil')
                ->url(fn (): string => CaptureSignature::getUrl(['interventionId' => $interventionId]))
                ->openUrlInNewTab(),
            Action::make('captureSketch')
                ->label('Disegno')
                ->icon('heroicon-o-pencil-square')
                ->url(fn (): string => CaptureSketch::getUrl(['interventionId' => $interventionId]))
                ->openUrlInNewTab(),
            Action::make('downloadReport')
                ->label(__('interventions.voice_report.download_pdf'))
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () use ($interventionId): StreamedResponse {
                    $intervention = Intervention::query()->findOrFail($interventionId);
                    $pdf = app(GenerateInterventionReportPdfAction::class)->execute($intervention);

                    return response()->streamDownload(
                        static fn () => print ($pdf['content']),
                        $pdf['filename'],
                        ['Content-Type' => $pdf['mime']],
                    );
                }),
            Action::make('showToCustomer')
                ->label(__('interventions.voice_report.show_to_customer'))
                ->icon('heroicon-o-device-phone-mobile')
                ->color('success')
                ->url(fn (): string => app(CreateInterventionReportShareLinkAction::class)
                    ->execute(Intervention::query()->findOrFail($interventionId), (int) Auth::id())
                    ->publicUrl())
                ->openUrlInNewTab(),
            Action::make('shareReport')
                ->label(__('interventions.voice_report.share_link'))
                ->icon('heroicon-o-link')
                ->modalHeading(__('interventions.voice_report.share_link'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel(__('interventions.voice_report.close'))
                ->modalContent(function () use ($interventionId) {
                    $intervention = Intervention::query()->findOrFail($interventionId);
                    $share = app(CreateInterventionReportShareLinkAction::class)
                        ->execute($intervention, (int) Auth::id());

                    return view('interventions::filament.share-report-link', [
                        'url' => $share->publicUrl(),
                    ]);
                }),
        ];
    }
}
