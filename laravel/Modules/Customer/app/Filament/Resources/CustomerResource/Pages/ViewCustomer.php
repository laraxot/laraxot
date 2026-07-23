<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\CustomerResource\Pages;

use Carbon\CarbonImmutable;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Modules\Customer\Actions\RestoreCustomerCascadeAction;
use Modules\Customer\Filament\Resources\CustomerResource;
use Modules\Customer\Models\Customer;
use Modules\WorkOrder\Actions\GenerateCustomerReportAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewCustomer extends XotBaseViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportCustomerReport')
                ->label('Rendicontazione')
                ->icon('heroicon-o-document-chart-bar')
                ->color('success')
                ->form([
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
                    $format = (string) ($data['format'] ?? 'pdf');
                    $from = CarbonImmutable::parse((string) $data['from'])->startOfDay();
                    $to = CarbonImmutable::parse((string) $data['to'])->endOfDay();

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
            Actions\Action::make('restoreCascade')
                ->label(__('customers.actions.restore_cascade'))
                ->icon('heroicon-o-arrow-path')
                ->visible(fn (): bool => $this->getRecord()->trashed())
                ->requiresConfirmation()
                ->action(function (): void {
                    app(RestoreCustomerCascadeAction::class)->execute($this->getRecord());
                }),
            Actions\EditAction::make(),
        ];
    }
}
