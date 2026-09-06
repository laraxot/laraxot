<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\CustomerResource\Pages;

use Carbon\CarbonImmutable;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Infolists\Components\Entry;
use Filament\Schemas\Components\Component;
use Modules\Customer\Actions\RestoreCustomerCascadeAction;
use Modules\Customer\Filament\Resources\CustomerResource;
use Modules\Customer\Models\Customer;
use Modules\WorkOrder\Actions\GenerateCustomerReportAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewCustomer extends XotBaseViewRecord
{
    protected static string $resource = CustomerResource::class;

    /**
     * @return array<string, Component|Entry>
     */
    protected function getInfolistSchema(): array
    {
        return CustomerResource::getInfolistSchema();
    }

    protected function getHeaderActions(): array
    {
        return [
            'exportCustomerReport' => Actions\Action::make('exportCustomerReport')
                ->label('Rendicontazione')
                ->icon('heroicon-o-document-chart-bar')
                ->color('success')
                ->schema([
                    Radio::make('format')
                        ->label('Formato')
                        ->options([
                            'pdf' => 'PDF',
                            'docx' => 'Word (DOCX)',
                        ])
                        ->default('pdf')
                        ->required()
                        ->inline(),
                    DatePicker::make('from')
                        ->label('Da data')
                        ->displayFormat('d/m/Y')
                        ->default(now()->startOfMonth()->toDateString())
                        ->required(),
                    DatePicker::make('to')
                        ->label('A data')
                        ->displayFormat('d/m/Y')
                        ->default(now()->endOfMonth()->toDateString())
                        ->required(),
                ])
                ->action(function (Customer $record, array $data): mixed {
                    $formatValue = $data['format'] ?? 'pdf';
                    $format = match (is_string($formatValue) ? $formatValue : 'pdf') {
                        'docx' => 'docx',
                        default => 'pdf',
                    };
                    $fromValue = $data['from'];
                    $from = CarbonImmutable::parse(is_string($fromValue) ? $fromValue : '')->startOfDay();
                    $toValue = $data['to'];
                    $to = CarbonImmutable::parse(is_string($toValue) ? $toValue : '')->endOfDay();

                    $result = app(GenerateCustomerReportAction::class)->execute(
                        customer: $record,
                        from: $from,
                        to: $to,
                        format: $format,
                    );

                    return response()->streamDownload(
                        function () use ($result): void {
                            echo $result['content'];
                        },
                        $result['filename'],
                        ['Content-Type' => $result['mime']],
                    );
                }),
            'restoreCascade' => Actions\Action::make('restoreCascade')
                ->label(__('customers.actions.restore_cascade'))
                ->icon('heroicon-o-arrow-path')
                ->visible(function (): bool {
                    $record = $this->getRecord();

                    return $record instanceof Customer && $record->trashed();
                })
                ->requiresConfirmation()
                ->action(function (): void {
                    $record = $this->getRecord();
                    if (! $record instanceof Customer) {
                        return;
                    }
                    app(RestoreCustomerCascadeAction::class)->execute($record);
                }),
            'edit' => Actions\EditAction::make(),
        ];
    }
}
