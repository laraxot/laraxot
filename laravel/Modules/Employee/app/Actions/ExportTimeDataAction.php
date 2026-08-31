<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use BackedEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Modules\Employee\Models\WorkHour;
use Spatie\QueueableAction\QueueableAction;

/**
 * Export time data for employee in various formats.
 *
 * Replicates dipendentincloud.it export functionality with enhanced features.
 */
class ExportTimeDataAction
{
    use QueueableAction;

    /**
     * Execute time data export.
     */
    public function execute(int|string $userId, Carbon $startDate, Carbon $endDate, string $format = 'xlsx'): string
    {
        // Ottieni dati completi per il periodo
        $timeData = $this->getTimeDataForExport($userId, $startDate, $endDate);

        // Genera file in base al formato
        return match ($format) {
            'xlsx' => $this->exportToExcel($timeData, $userId, $startDate, $endDate),
            'csv' => $this->exportToCsv($timeData, $userId, $startDate, $endDate),
            'pdf' => $this->exportToPdf($timeData, $userId, $startDate, $endDate),
            default => throw new InvalidArgumentException("Unsupported export format: {$format}"),
        };
    }

    /**
     * Ottieni dati strutturati per export.
     *
     * @return array{employee: array<string, array<string, int|string>|int|string>, period: array{start: string, end: string, days: int}, summary: array<string, int>, weekData: array<string, mixed>, entries: array<int, array{date: string, time: string, type: string, status: string, location: string, notes: string}>, generatedAt: string}
     */
    private function getTimeDataForExport(int|string $userId, Carbon $startDate, Carbon $endDate): array
    {
        // Usa Action esistente per ottenere dati base
        $baseData = app(BuildWorkHoursForRangeAction::class)->execute($userId, $startDate, $endDate);

        // Aggiungi dati dettagliati per export
        $weekData = app(BuildWeeklyTimeTableAction::class)->execute($userId, $startDate, $endDate);
        $employeeData = app(GetCurrentEmployeeDataAction::class)->execute($userId);

        // Ottieni tutte le timbrature del periodo per dettagli
        $allEntries = WorkHour::query()
            ->where('employee_id', $userId)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->orderBy('timestamp', 'asc')
            ->get();

        /** @var array{id: int, name: string, email: string, status: string, employeeNumber?: string, hireDate?: string, department?: array{id: int, name: string}, position?: array{id: int, name: string}} $employeeDataTyped */
        $employeeDataTyped = $employeeData;

        /** @var array{workedMinutes: int, addedMinutes: int, reducedMinutes: int, contractMinutes: int} $summaryTyped */
        $summaryTyped = $baseData['summary'];

        return [
            'employee' => $employeeDataTyped,
            'period' => [
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y'),
                'days' => (int) ($startDate->diffInDays($endDate) + 1),
            ],
            'summary' => $summaryTyped,
            'weekData' => $weekData,
            'entries' => $allEntries->map(fn (WorkHour $entry): array => [
                'date' => $entry->timestamp->format('d/m/Y'),
                'time' => $entry->timestamp->format('H:i'),
                'type' => ($entry->type instanceof BackedEnum) ? $entry->type->value : ((string) $entry->type),
                'status' => ($entry->status instanceof BackedEnum)
                    ? $entry->status->value
                    : ((string) $entry->status),
                'location' => $entry->location_name ?? '',
                'notes' => $entry->notes ?? '',
            ])->values()->all(),
            'generatedAt' => Carbon::now()->format('d/m/Y H:i'),
        ];
    }

    /**
     * Export to Excel format (replicating dipendentincloud.it).
     *
     * @param  array{employee?: array<string, mixed>, period?: array<string, mixed>, summary?: array<string, int>, weekData?: array<string, mixed>, entries?: array<int, array<string, string>>, generatedAt?: string}  $data
     */
    private function exportToExcel(array $data, int|string $userId, Carbon $startDate, Carbon $endDate): string
    {
        $filename = "timbrature_{$userId}_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.xlsx";

        // Qui implementeresti l'export Excel usando Laravel Excel o simile
        // Per ora creo un CSV strutturato
        $csvData = $this->buildCsvData($data);

        Storage::put("exports/time_data/{$filename}", $csvData);

        return Storage::path("exports/time_data/{$filename}");
    }

    /**
     * Export to CSV format.
     *
     * @param  array{employee?: array<string, mixed>, period?: array<string, mixed>, summary?: array<string, int>, weekData?: array<string, mixed>, entries?: array<int, array<string, string>>, generatedAt?: string}  $data
     */
    private function exportToCsv(array $data, int|string $userId, Carbon $startDate, Carbon $endDate): string
    {
        $filename = "timbrature_{$userId}_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.csv";
        $csvData = $this->buildCsvData($data);

        Storage::put("exports/time_data/{$filename}", $csvData);

        return Storage::path("exports/time_data/{$filename}");
    }

    /**
     * Export to PDF format.
     *
     * @param  array{employee?: array<string, mixed>, period?: array<string, mixed>, summary?: array<string, int>, weekData?: array<string, mixed>, entries?: array<int, array<string, string>>, generatedAt?: string}  $data
     */
    private function exportToPdf(array $data, int|string $userId, Carbon $startDate, Carbon $endDate): string
    {
        $filename = "timbrature_{$userId}_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.pdf";

        // Qui implementeresti l'export PDF usando DomPDF o simile
        $pdfContent = $this->buildPdfContent($data);

        Storage::put("exports/time_data/{$filename}", $pdfContent);

        return Storage::path("exports/time_data/{$filename}");
    }

    /**
     * Costruisce contenuto CSV.
     *
     * @param  array{employee?: array<string, mixed>, period?: array<string, mixed>, summary?: array<string, int>, weekData?: array<string, mixed>, entries?: array<int, array<string, string>>, generatedAt?: string}  $data
     */
    private function buildCsvData(array $data): string
    {
        $csv = [];

        // Header
        $csv[] = [
            'Data',
            'Ora',
            'Tipo',
            'Stato',
            'Ubicazione',
            'Note',
        ];

        // Dati entries
        /** @var array<int, array{date?: string, time?: string, type?: string, status?: string, location?: string, notes?: string}> $entriesData */
        $entriesData = $data['entries'] ?? [];
        if (! is_array($entriesData)) {
            $entriesData = [];
        }

        foreach ($entriesData as $entry) {
            if (! is_array($entry)) {
                continue;
            }
            $csv[] = [
                is_string($entry['date'] ?? null) ? $entry['date'] : '',
                is_string($entry['time'] ?? null) ? $entry['time'] : '',
                $this->translateEntryType(is_string($entry['type'] ?? null) ? $entry['type'] : ''),
                $this->translateEntryStatus(is_string($entry['status'] ?? null) ? $entry['status'] : ''),
                is_string($entry['location'] ?? null) ? $entry['location'] : '',
                is_string($entry['notes'] ?? null) ? $entry['notes'] : '',
            ];
        }

        // Summary
        /** @var array<string, int> $summary */
        $summary = $data['summary'] ?? [];
        $csv[] = ['', '', '', '', '', ''];
        $csv[] = ['RIEPILOGO', '', '', '', '', ''];

        $workedMinutes = is_int($summary['workedMinutes'] ?? null) ? $summary['workedMinutes'] : 0;
        $contractMinutes = is_int($summary['contractMinutes'] ?? null) ? $summary['contractMinutes'] : 0;

        $csv[] = ['Ore Lavorate', $this->formatHours($workedMinutes), '', '', '', ''];
        $csv[] = ['Ore Contrattuali', $this->formatHours($contractMinutes), '', '', '', ''];

        // Converti in stringa CSV
        $output = '';
        foreach ($csv as $row) {
            $output .= '"'.implode('","', $row).'"'."\n";
        }

        return $output;
    }

    /**
     * Costruisce contenuto PDF (placeholder).
     *
     * @param  array{employee?: array<string, mixed>, period?: array<string, mixed>, summary?: array<string, int>, weekData?: array<string, mixed>, entries?: array<int, array<string, string>>, generatedAt?: string}  $data
     */
    private function buildPdfContent(array $data): string
    {
        // Placeholder per implementazione PDF futura
        /** @var array{name?: string} $employee */
        $employee = $data['employee'] ?? [];

        /** @var array{start?: string, end?: string} $period */
        $period = $data['period'] ?? [];

        $employeeName = is_string($employee['name'] ?? null) ? $employee['name'] : '';
        $periodStart = is_string($period['start'] ?? null) ? $period['start'] : '';
        $periodEnd = is_string($period['end'] ?? null) ? $period['end'] : '';

        return 'PDF Export - Employee: '.$employeeName."\nPeriod: ".$periodStart.' - '.$periodEnd."\n";
    }

    /**
     * Traduce tipo entry per export.
     */
    private function translateEntryType(string $type): string
    {
        return match ($type) {
            'clock_in' => 'Entrata',
            'clock_out' => 'Uscita',
            'break_start' => 'Inizio Pausa',
            'break_end' => 'Fine Pausa',
            default => ucfirst($type),
        };
    }

    /**
     * Traduce stato entry per export.
     */
    private function translateEntryStatus(string $status): string
    {
        return match ($status) {
            'pending' => 'In Attesa',
            'approved' => 'Approvata',
            'rejected' => 'Rifiutata',
            'cancelled' => 'Cancellata',
            default => ucfirst($status),
        };
    }

    /**
     * Formatta minuti in ore:minuti.
     */
    private function formatHours(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        return sprintf('%d:%02d', $hours, $mins);
    }
}
