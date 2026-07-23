<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\AuditLogResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Modules\Platform\Filament\Resources\AuditLogResource;
use Modules\Platform\Models\AuditLog;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ListAuditLogs extends ListRecords
{
    protected static string $resource = AuditLogResource::class;

    /** @return array<int, Action> */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label(__('platform.audit_log.actions.export_csv'))
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (): StreamedResponse {
                    /** @var Builder<AuditLog> $query */
                    $query = $this->getFilteredTableQuery();

                    $fileName = 'audit-log-' . now()->format('Ymd-His') . '.csv';

                    return response()->streamDownload(function () use ($query): void {
                        $out = fopen('php://output', 'w');
                        if ($out === false) {
                            return;
                        }

                        fputcsv($out, [
                            'created_at',
                            'actor',
                            'action',
                            'subject_type',
                            'subject_id',
                            'panel_id',
                            'route',
                            'ip',
                        ]);

                        $query->orderByDesc('created_at')->chunk(500, function ($logs) use ($out): void {
                            foreach ($logs as $log) {
                                $actor = $log->actor?->email ?? $log->actor?->name ?? '';
                                fputcsv($out, [
                                    optional($log->created_at)->toISOString(),
                                    $actor,
                                    (string) $log->action,
                                    (string) $log->subject_type,
                                    (string) $log->subject_id,
                                    (string) ($log->panel_id ?? ''),
                                    (string) ($log->route ?? ''),
                                    (string) ($log->ip ?? ''),
                                ]);
                            }
                        });

                        fclose($out);
                    }, $fileName, [
                        'Content-Type' => 'text/csv; charset=UTF-8',
                    ]);
                }),
        ];
    }
}

