<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\Concerns;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Modules\Quotation\Actions\RecordSiteSurveyAction;
use Modules\Quotation\DTO\SiteSurveyAttachment;
use Modules\Quotation\Models\SiteSurvey;

/**
 * Azione "Registra rilievo": chiude il sopralluogo e ne salva gli allegati.
 *
 * Sta in un trait perche' serve identica sulla pagina di visualizzazione e su
 * quella di modifica: il tecnico rientra dal cantiere e la cerca dove capita.
 */
trait RecordsSiteSurvey
{
    protected function recordSurveyAction(): Action
    {
        return Action::make('recordSurvey')
            ->label(__('site_surveys.actions.record'))
            ->icon('heroicon-o-camera')
            ->color('primary')
            ->modalSubmitActionLabel(__('site_surveys.actions.record'))
            ->schema([
                Textarea::make('notes')
                    ->label(__('site_surveys.fields.notes'))
                    ->rows(4)
                    ->default(function (): ?string {
                        $survey = $this->currentSiteSurvey();
                        $notes = $survey?->notes;

                        return is_string($notes) ? $notes : null;
                    })
                    ->helperText(__('site_surveys.actions.record_hint')),

                Repeater::make('attachments')
                    ->label(__('site_surveys.actions.attachments'))
                    ->helperText(__('site_surveys.hints.no_attachments'))
                    ->schema([
                        Select::make('kind')
                            ->label(__('site_surveys.attachments.kind'))
                            ->options(self::attachmentKindOptions())
                            ->default('photo')
                            ->required(),
                        TextInput::make('title')
                            ->label(__('site_surveys.attachments.file_title'))
                            ->maxLength(255),
                        FileUpload::make('path')
                            ->label(__('site_surveys.attachments.file'))
                            ->disk('public')
                            ->directory('site-surveys')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->addActionLabel(__('site_surveys.actions.attachments'))
                    ->default([]),
            ])
            ->action(function (array $data): void {
                $survey = $this->currentSiteSurvey();

                if (! $survey instanceof SiteSurvey) {
                    return;
                }

                $attachments = self::toAttachments($data['attachments'] ?? []);

                $notes = $data['notes'] ?? null;

                app(RecordSiteSurveyAction::class)->execute(
                    survey: $survey,
                    attachments: $attachments,
                    notes: is_string($notes) && trim($notes) !== '' ? $notes : null,
                );

                Notification::make()
                    ->success()
                    ->title(__('site_surveys.actions.recorded_title'))
                    ->body(__('site_surveys.actions.recorded_body', ['count' => count($attachments)]))
                    ->send();
            });
    }

    /**
     * @return array<string, string>
     */
    protected static function attachmentKindOptions(): array
    {
        return [
            'photo' => __('site_surveys.attachments.kinds.photo'),
            'video' => __('site_surveys.attachments.kinds.video'),
            'audio' => __('site_surveys.attachments.kinds.audio'),
        ];
    }

    /**
     * Le righe senza file vengono scartate: un allegato senza percorso non e' un
     * allegato, e il DTO lo rifiuterebbe comunque.
     *
     * @return array<int, SiteSurveyAttachment>
     */
    protected static function toAttachments(mixed $rows): array
    {
        if (! is_array($rows)) {
            return [];
        }

        $attachments = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $rawPath = $row['path'] ?? null;

            // FileUpload restituisce un array quando gestisce piu' file.
            if (is_array($rawPath)) {
                $rawPath = reset($rawPath);
            }

            $path = is_string($rawPath) ? trim($rawPath) : '';

            if ($path === '') {
                continue;
            }

            $kind = is_string($row['kind'] ?? null) ? $row['kind'] : 'photo';

            if (! in_array($kind, SiteSurveyAttachment::KINDS, true)) {
                $kind = 'photo';
            }

            $title = $row['title'] ?? null;

            $attachments[] = new SiteSurveyAttachment(
                kind: $kind,
                path: $path,
                disk: 'public',
                title: is_string($title) && trim($title) !== '' ? $title : null,
            );
        }

        return $attachments;
    }

    private function currentSiteSurvey(): ?SiteSurvey
    {
        $record = $this->getRecord();

        return $record instanceof SiteSurvey ? $record : null;
    }
}
