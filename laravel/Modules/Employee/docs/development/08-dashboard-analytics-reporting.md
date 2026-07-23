# 08 - Dashboard Analytics e Reporting

## Panoramica
Sistema completo di analytics e reporting per fornire insights dettagliati su presenze, performance, costi del personale e trend aziendali con dashboard interattive e report personalizzabili.

## Obiettivi
- Fornire dashboard executive per management
- Creare KPI e metriche chiave HR
- Implementare report personalizzabili
- Analizzare trend presenze e performance
- Calcolare costi del personale in tempo reale
- Fornire insights predittivi per decisioni strategiche

## Funzionalità da Implementare

### 1. Dashboard Executive

#### 1.1 KPI Dashboard per Management
**Obiettivo**: Fornire vista d'insieme delle metriche chiave HR

**Implementazione Step-by-Step**:

1. **Creare Widget ExecutiveDashboardWidget**
```php
// app/Filament/Widgets/ExecutiveDashboardWidget.php
class ExecutiveDashboardWidget extends XotBaseWidget
{
    protected static string $view = 'filament.widgets.executive-dashboard';
    protected int | string | array $columnSpan = 'full';
    
    public function getViewData(): array
    {
        $currentMonth = now();
        $previousMonth = now()->subMonth();
        
        return [
            'employee_stats' => $this->getEmployeeStats(),
            'attendance_stats' => $this->getAttendanceStats($currentMonth),
            'cost_analysis' => $this->getCostAnalysis($currentMonth),
            'performance_metrics' => $this->getPerformanceMetrics(),
            'trends' => $this->getTrendAnalysis($currentMonth, $previousMonth)
        ];
    }
    
    private function getEmployeeStats(): array
    {
        return [
            'total_employees' => Employee::where('is_active', true)->count(),
            'new_hires_month' => Employee::whereMonth('hire_date', now()->month)->count(),
            'departures_month' => Employee::whereMonth('termination_date', now()->month)->count(),
            'departments_count' => Department::count(),
            'average_tenure' => $this->calculateAverageTenure(),
            'turnover_rate' => $this->calculateTurnoverRate()
        ];
    }
    
    private function getAttendanceStats(Carbon $month): array
    {
        $totalWorkingDays = $this->getWorkingDaysInMonth($month);
        
        return [
            'attendance_rate' => $this->calculateAttendanceRate($month),
            'average_hours_per_day' => $this->calculateAverageHoursPerDay($month),
            'overtime_hours' => $this->calculateOvertimeHours($month),
            'sick_days' => $this->calculateSickDays($month),
            'vacation_days' => $this->calculateVacationDays($month),
            'working_days' => $totalWorkingDays
        ];
    }
    
    private function getCostAnalysis(Carbon $month): array
    {
        return [
            'total_payroll' => $this->calculateTotalPayroll($month),
            'cost_per_employee' => $this->calculateCostPerEmployee($month),
            'overtime_costs' => $this->calculateOvertimeCosts($month),
            'benefits_costs' => $this->calculateBenefitsCosts($month),
            'recruitment_costs' => $this->calculateRecruitmentCosts($month)
        ];
    }
}
```

2. **Creare Service AnalyticsService**
```php
// app/Services/AnalyticsService.php
class AnalyticsService
{
    public function calculateAttendanceRate(Carbon $month): float
    {
        $totalExpectedHours = Employee::where('is_active', true)->count() * 
                             $this->getWorkingDaysInMonth($month) * 8;
                             
        $actualHours = Attendance::whereMonth('date', $month->month)
                                ->whereYear('date', $month->year)
                                ->sum('hours_worked');
                                
        return $totalExpectedHours > 0 ? ($actualHours / $totalExpectedHours) * 100 : 0;
    }
    
    public function calculateTurnoverRate(): float
    {
        $yearStart = now()->startOfYear();
        $averageEmployees = Employee::where('hire_date', '<=', $yearStart)->count();
        $departures = Employee::whereYear('termination_date', now()->year)->count();
        
        return $averageEmployees > 0 ? ($departures / $averageEmployees) * 100 : 0;
    }
    
    public function getProductivityMetrics(Carbon $period): array
    {
        return [
            'projects_completed' => $this->getCompletedProjects($period),
            'goals_achieved' => $this->getAchievedGoals($period),
            'training_hours' => $this->getTrainingHours($period),
            'performance_scores' => $this->getAveragePerformanceScores($period)
        ];
    }
    
    public function getDepartmentAnalytics(): array
    {
        return Department::withCount(['employees'])
            ->with(['employees' => function ($query) {
                $query->selectRaw('department_id, AVG(performance_score) as avg_performance')
                      ->groupBy('department_id');
            }])
            ->get()
            ->map(function ($dept) {
                return [
                    'name' => $dept->name,
                    'employee_count' => $dept->employees_count,
                    'avg_performance' => $dept->employees->avg('performance_score'),
                    'attendance_rate' => $this->getDepartmentAttendanceRate($dept->id),
                    'cost_center' => $this->getDepartmentCosts($dept->id)
                ];
            })
            ->toArray();
    }
}
```

### 2. Report Personalizzabili

#### 2.1 Sistema Report Builder
**Obiettivo**: Permettere agli utenti di creare report personalizzati

**Implementazione**:

1. **Creare Model Report**
```php
// app/Models/Report.php
class Report extends Model
{
    protected $fillable = [
        'name',
        'description',
        'report_type', // attendance, payroll, performance, custom
        'filters',
        'columns',
        'grouping',
        'sorting',
        'chart_type',
        'created_by',
        'is_public',
        'schedule', // daily, weekly, monthly, null
        'recipients'
    ];

    protected $casts = [
        'filters' => 'array',
        'columns' => 'array',
        'grouping' => 'array',
        'sorting' => 'array',
        'recipients' => 'array',
        'is_public' => 'boolean'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
```

2. **Creare Filament Page ReportBuilderPage**
```php
// app/Filament/Pages/ReportBuilderPage.php
class ReportBuilderPage extends XotBasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.report-builder';
    
    public $reportType = 'attendance';
    public $selectedColumns = [];
    public $filters = [];
    public $groupBy = null;
    public $chartType = 'table';
    public $reportData = [];
    
    public function mount(): void
    {
        $this->loadDefaultColumns();
    }
    
    public function generateReport(): void
    {
        $this->reportData = app(ReportService::class)->generateReport([
            'type' => $this->reportType,
            'columns' => $this->selectedColumns,
            'filters' => $this->filters,
            'group_by' => $this->groupBy
        ]);
    }
    
    public function exportReport($format): StreamedResponse
    {
        return app(ReportService::class)->exportReport(
            $this->reportData,
            $format,
            $this->selectedColumns
        );
    }
}
```

3. **Creare Service ReportService**
```php
// app/Services/ReportService.php
class ReportService
{
    public function generateReport(array $config): array
    {
        $query = $this->buildQuery($config);
        $data = $query->get();
        
        if ($config['group_by']) {
            $data = $this->groupData($data, $config['group_by']);
        }
        
        return [
            'data' => $data,
            'summary' => $this->calculateSummary($data, $config),
            'charts' => $this->generateChartData($data, $config)
        ];
    }
    
    private function buildQuery(array $config): Builder
    {
        $query = match($config['type']) {
            'attendance' => $this->buildAttendanceQuery($config),
            'payroll' => $this->buildPayrollQuery($config),
            'performance' => $this->buildPerformanceQuery($config),
            default => Employee::query()
        };
        
        return $this->applyFilters($query, $config['filters'] ?? []);
    }
    
    private function buildAttendanceQuery(array $config): Builder
    {
        return Attendance::with(['employee:id,name,surname,department_id'])
            ->select($config['columns'] ?? ['*']);
    }
    
    public function exportReport(array $data, string $format, array $columns): StreamedResponse
    {
        return match($format) {
            'excel' => Excel::download(new ReportExport($data, $columns), 'report.xlsx'),
            'pdf' => PDF::loadView('reports.pdf', compact('data', 'columns'))->download('report.pdf'),
            'csv' => $this->exportToCsv($data, $columns)
        };
    }
}
```

### 3. Analytics Avanzate

#### 3.1 Predictive Analytics
**Obiettivo**: Fornire insights predittivi per decisioni strategiche

**Implementazione**:

1. **Creare Widget PredictiveAnalyticsWidget**
```php
// app/Filament/Widgets/PredictiveAnalyticsWidget.php
class PredictiveAnalyticsWidget extends XotBaseWidget
{
    protected static string $view = 'filament.widgets.predictive-analytics';
    
    public function getViewData(): array
    {
        return [
            'turnover_prediction' => $this->predictTurnover(),
            'capacity_forecast' => $this->forecastCapacity(),
            'cost_projection' => $this->projectCosts(),
            'performance_trends' => $this->analyzePerformanceTrends()
        ];
    }
    
    private function predictTurnover(): array
    {
        // Analisi trend dimissioni ultimi 12 mesi
        $monthlyTurnover = Employee::selectRaw('
                YEAR(termination_date) as year,
                MONTH(termination_date) as month,
                COUNT(*) as departures
            ')
            ->whereNotNull('termination_date')
            ->where('termination_date', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        // Calcola trend e predizione
        $trend = $this->calculateTrend($monthlyTurnover->pluck('departures')->toArray());
        
        return [
            'current_rate' => $this->calculateCurrentTurnoverRate(),
            'predicted_rate' => $trend['prediction'],
            'trend_direction' => $trend['direction'],
            'risk_level' => $this->assessTurnoverRisk($trend['prediction'])
        ];
    }
    
    private function forecastCapacity(): array
    {
        $currentCapacity = Employee::where('is_active', true)->count();
        $plannedHires = $this->getPlannedHires();
        $predictedDepartures = $this->predictDepartures();
        
        return [
            'current_capacity' => $currentCapacity,
            'projected_capacity_3m' => $currentCapacity + $plannedHires['3_months'] - $predictedDepartures['3_months'],
            'projected_capacity_6m' => $currentCapacity + $plannedHires['6_months'] - $predictedDepartures['6_months'],
            'capacity_gap' => $this->calculateCapacityGap()
        ];
    }
}
```

### 4. Real-time Monitoring

#### 4.1 Dashboard Live Metrics
**Obiettivo**: Monitoraggio in tempo reale delle metriche chiave

**Implementazione**:

1. **Creare Widget LiveMetricsWidget**
```php
// app/Filament/Widgets/LiveMetricsWidget.php
class LiveMetricsWidget extends XotBaseWidget
{
    protected static string $view = 'filament.widgets.live-metrics';
    protected static bool $isLazy = false;
    
    public function getViewData(): array
    {
        return [
            'employees_present' => $this->getCurrentlyPresent(),
            'late_arrivals' => $this->getLateArrivals(),
            'pending_approvals' => $this->getPendingApprovals(),
            'system_alerts' => $this->getSystemAlerts(),
            'real_time_stats' => $this->getRealTimeStats()
        ];
    }
    
    private function getCurrentlyPresent(): int
    {
        return Attendance::whereDate('date', today())
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->count();
    }
    
    private function getLateArrivals(): Collection
    {
        $standardStartTime = '09:00:00';
        
        return Attendance::with('employee')
            ->whereDate('date', today())
            ->whereTime('check_in', '>', $standardStartTime)
            ->limit(10)
            ->get();
    }
    
    private function getPendingApprovals(): array
    {
        return [
            'leave_requests' => LeaveRequest::where('status', 'pending')->count(),
            'expense_reports' => ExpenseReport::where('status', 'submitted')->count(),
            'shift_changes' => ShiftSubstitutionRequest::where('status', 'pending')->count()
        ];
    }
}
```

### 5. Automated Reporting

#### 5.1 Report Schedulati
**Obiettivo**: Generare e inviare report automaticamente

**Implementazione**:

1. **Creare Job GenerateScheduledReportJob**
```php
// app/Jobs/GenerateScheduledReportJob.php
class GenerateScheduledReportJob implements ShouldQueue
{
    public function __construct(
        private Report $report
    ) {}
    
    public function handle(): void
    {
        $reportData = app(ReportService::class)->generateReport([
            'type' => $this->report->report_type,
            'columns' => $this->report->columns,
            'filters' => $this->report->filters,
            'group_by' => $this->report->grouping
        ]);
        
        $exportFile = app(ReportService::class)->exportReport(
            $reportData,
            'excel',
            $this->report->columns
        );
        
        // Invia via email ai destinatari
        foreach ($this->report->recipients as $email) {
            Mail::to($email)->send(new ScheduledReportMail($this->report, $exportFile));
        }
    }
}
```

2. **Creare Command per scheduling**
```php
// app/Console/Commands/GenerateScheduledReportsCommand.php
class GenerateScheduledReportsCommand extends Command
{
    protected $signature = 'reports:generate-scheduled';
    
    public function handle(): void
    {
        $reports = Report::whereNotNull('schedule')->get();
        
        foreach ($reports as $report) {
            if ($this->shouldGenerateReport($report)) {
                GenerateScheduledReportJob::dispatch($report);
            }
        }
    }
    
    private function shouldGenerateReport(Report $report): bool
    {
        return match($report->schedule) {
            'daily' => true,
            'weekly' => now()->dayOfWeek === 1, // Lunedì
            'monthly' => now()->day === 1,
            default => false
        };
    }
}
```

## Checklist Implementazione

### Phase 1: Base Analytics
- [ ] Creare AnalyticsService per calcoli metriche
- [ ] Implementare ExecutiveDashboardWidget
- [ ] Configurare KPI e metriche base

### Phase 2: Report Builder
- [ ] Creare Model Report e migrations
- [ ] Implementare ReportBuilderPage
- [ ] Creare ReportService per generazione

### Phase 3: Advanced Analytics
- [ ] Implementare PredictiveAnalyticsWidget
- [ ] Creare algoritmi trend analysis
- [ ] Aggiungere capacity forecasting

### Phase 4: Real-time Monitoring
- [ ] Creare LiveMetricsWidget
- [ ] Implementare WebSocket per updates live
- [ ] Sistema alerting automatico

### Phase 5: Automated Reporting
- [ ] Implementare scheduled reports
- [ ] Creare sistema email automatico
- [ ] Dashboard gestione report schedulati

## Note Tecniche

### Performance
- Cache per metriche pesanti
- Query ottimizzate per grandi dataset
- Indicizzazione database appropriata

### Scalabilità
- Queue per report pesanti
- Pagination per dataset grandi
- Streaming per export voluminosi

### Sicurezza
- Controllo accesso ai dati sensibili
- Audit trail per accessi report
- Crittografia dati esportati

Questo sistema fornirà analytics complete e insights strategici per decisioni HR data-driven.
