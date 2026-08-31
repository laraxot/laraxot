---
title: "UI — indice della documentazione"
description: "Documentazione del modulo UI: componenti di interfaccia condivisi."
module: UI
tags: [ui, documentazione, modulo, laraxot]
status: active
repository: https://github.com/laraxot/module_ui_fila5
related:
  - ./00-index.md
  - ./index.md
  - ../../../../docs/wiki/audits/docs-redundancy-audit.md
issues: https://github.com/laraxot/module_ui_fila5/issues
discussions: https://github.com/laraxot/module_ui_fila5/discussions
---

# UI Module Documentation

## Overview
The UI module provides shared user interface components, widgets, and styling for the Laraxot system. It includes specialized components for chart rendering, PDF generation interfaces, and survey data visualization. The module integrates with Chart and Quaeris modules to provide professional UI experiences for survey data analysis and reporting.

## Key Features
- **Chart Components**: Reusable chart components with multiple visualization options
- **PDF Generation UI**: Interfaces for PDF report generation with chart embedding
- **Survey Widgets**: Specialized widgets for survey data visualization
- **Responsive Design**: Mobile-first responsive design principles
- **Theme Support**: Integration with Laraxot's theme system
- **Accessibility**: WCAG 2.1 compliant components

## Core Components

### Chart Components
- `ChartWidget` - Interactive chart widget with export capabilities
- `ChartRenderer` - Server-side chart rendering component
- `ChartExportModal` - Modal interface for chart export options
- `PdfChartPreview` - Preview component for charts in PDF context

### PDF Components
- `PdfGeneratorForm` - Form for PDF generation configuration
- `PdfPreview` - Preview component for PDF content
- `PdfExportButton` - Specialized button for PDF exports
- `PdfTemplateSelector` - Component for selecting PDF templates

### Survey Components
- `QuestionChartAnswersTableWidget` - Table widget for question answers
- `SurveyResponseView` - View component for survey responses
- `SurveyFilterPanel` - Filtering interface for survey data

## Chart Integration with PDF Generation

### Chart Component Architecture with Dual Approach Support
```blade
{{-- ChartWidget Component with Support for Both Dynamic and Flip Approaches --}}
<x-filament-widgets::widget class="chart-widget">
    <div class="chart-container" id="chart-{{ $chartId }}">
        <div class="chart-header">
            <select id="data-approach-{{ $chartId }}" onchange="changeDataApproach('{{ $chartId }}')">
                <option value="dynamic" {{ $selectedApproach === 'dynamic' ? 'selected' : '' }}>Dynamic Model</option>
                <option value="flip" {{ $selectedApproach === 'flip' ? 'selected' : '' }}>Flip Approach</option>
            </select>
        </div>
        
        <canvas id="chart-canvas-{{ $chartId }}"></canvas>
        
        <div class="chart-actions">
            <x-ui::button 
                type="button" 
                onclick="exportChartToPng('{{ $chartId }}')"
                class="btn-secondary"
            >
                Export PNG
            </x-ui::button>
            
            <x-ui::button 
                type="button" 
                onclick="exportChartToPdf('{{ $chartId }}')"
                class="btn-primary"
            >
                Export PDF
            </x-ui::button>
        </div>
    </div>
    
    <script>
        function changeDataApproach(chartId) {
            const approachSelect = document.getElementById('data-approach-' + chartId);
            const selectedApproach = approachSelect.value;
            
            // Reload chart with new approach
            fetch('/api/charts/data/' + chartId + '?approach=' + selectedApproach, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update chart with new data
                updateChart(chartId, data);
            });
        }
        
        function exportChartToPng(chartId) {
            const canvas = document.getElementById('chart-canvas-' + chartId);
            const pngData = canvas.toDataURL('image/png');
            
            // Get selected approach
            const approachSelect = document.getElementById('data-approach-' + chartId);
            const selectedApproach = approachSelect.value;
            
            // Send to server for processing
            fetch('/api/charts/export/png', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    chartId: chartId,
                    pngData: pngData,
                    approach: selectedApproach
                })
            });
        }
        
        function exportChartToPdf(chartId) {
            // Get selected approach
            const approachSelect = document.getElementById('data-approach-' + chartId);
            const selectedApproach = approachSelect.value;
            
            // Generate PDF with chart embedded
            fetch('/api/charts/export/pdf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    chartId: chartId,
                    engine: 'html2pdf', // or 'spatie'
                    approach: selectedApproach
                })
            }).then(response => {
                if (response.ok) {
                    response.blob().then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'chart_' + chartId + '.pdf';
                        a.click();
                        window.URL.revokeObjectURL(url);
                    });
                }
            });
        }
        
        function updateChart(chartId, data) {
            const ctx = document.getElementById('chart-canvas-' + chartId).getContext('2d');
            
            // Destroy existing chart if it exists
            if (window.charts && window.charts[chartId]) {
                window.charts[chartId].destroy();
            }
            
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Responses',
                        data: data.values,
                        backgroundColor: [
                            '#3b82f6', '#ef4444', '#10b981', '#f59e0b',
                            '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Survey Responses'
                        }
                    }
                }
            });
            
            // Store chart reference
            if (!window.charts) window.charts = {};
            window.charts[chartId] = chart;
        }
    </script>
</x-filament-widgets::widget>
```

### PDF Generation Component
```blade
{{-- PdfGeneratorForm Component --}}
<x-ui::form wire:submit.prevent="generatePdf">
    <div class="pdf-generator-form">
        <div class="form-group">
            <x-ui::label for="survey_pdf_id">Survey PDF Template</x-ui::label>
            <x-ui::select 
                id="survey_pdf_id" 
                wire:model="surveyPdfId"
                class="form-control"
            >
                <option value="">Select a template</option>
                @foreach($surveyPdfs as $pdf)
                    <option value="{{ $pdf->id }}">{{ $pdf->name }}</option>
                @endforeach
            </x-ui::select>
        </div>
        
        <div class="form-group">
            <x-ui::label for="pdf_engine">PDF Engine</x-ui::label>
            <x-ui::select 
                id="pdf_engine" 
                wire:model="pdfEngine"
                class="form-control"
            >
                <option value="html2pdf">HTML2PDF</option>
                <option value="spatie">Spatie PDF</option>
            </x-ui::select>
        </div>
        
        <div class="form-group">
            <x-ui::label for="include_charts">Include Charts</x-ui::label>
            <x-ui::checkbox 
                id="include_charts" 
                wire:model="includeCharts"
            />
        </div>
        
        <div class="form-actions">
            <x-ui::button type="submit" class="btn-primary">
                Generate PDF
            </x-ui::button>
        </div>
    </div>
</x-ui::form>
```

## Advanced Chart Integration

### Chart with Dynamic Data from LimeSurvey
```blade
{{-- Chart with LimeSurvey Dynamic Model Integration --}}
<div class="chart-with-dynamic-data">
    <div class="chart-header">
        <h3>{{ $questionText }}</h3>
        <p>Survey: {{ $surveyId }} | Question: {{ $questionId }}</p>
    </div>
    
    <div class="chart-content">
        <canvas id="dynamic-chart-{{ $chartId }}"></canvas>
        
        <div class="chart-data-source">
            <small>Data from lime_survey_{{ $surveyId }} table</small>
        </div>
    </div>
    
    <script>
        // Initialize chart with data from dynamic model
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dynamic-chart-{{ $chartId }}').getContext('2d');
            
            // Fetch data using dynamic model
            fetch('/api/limesurvey/data/{{ $surveyId }}/{{ $questionId }}')
                .then(response => response.json())
                .then(data => {
                    new Chart(ctx, {
                        type: '{{ $chartType }}',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Responses',
                                data: data.values,
                                backgroundColor: [
                                    '#3b82f6', '#ef4444', '#10b981', '#f59e0b',
                                    '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: '{{ $questionText }}'
                                }
                            }
                        }
                    });
                });
        });
    </script>
</div>
```

### PDF Template Component
```blade
{{-- PDF Template with Chart Embedding --}}
<div class="pdf-template" style="font-family: Arial, sans-serif; max-width: 21cm; margin: 0 auto;">
    <header class="pdf-header">
        <h1>{{ $title }}</h1>
        <p>Generated on: {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </header>
    
    <main class="pdf-content">
        @foreach($charts as $chart)
            <section class="chart-section">
                <h2>{{ $chart['title'] }}</h2>
                @if($chart['image_path'])
                    <img src="{{ public_path($chart['image_path']) }}" 
                         alt="{{ $chart['title'] }}"
                         style="width: 100%; height: auto; border: 1px solid #ddd;">
                @else
                    <div class="chart-placeholder">
                        <p>No chart data available for: {{ $chart['title'] }}</p>
                    </div>
                @endif
                
                @if($chart['data_table'])
                    <table class="chart-data-table">
                        <thead>
                            <tr>
                                <th>Response</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chart['data_table'] as $row)
                                <tr>
                                    <td>{{ $row['label'] }}</td>
                                    <td>{{ $row['count'] }}</td>
                                    <td>{{ $row['percentage'] }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </section>
        @endforeach
    </main>
    
    <footer class="pdf-footer">
        <p>Page <span class="page-number"></span> of <span class="total-pages"></span></p>
    </footer>
</div>
```

## Server-Side Chart Generation for PDFs

### JpGraph Integration in Components
```php
use Modules\Limesurvey\Models\SurveyResponse;

class PdfChartGenerator
{
    public function generateChartForPdf(string $surveyId, string $fieldName, array $data): string
    {
        $graph = new \Graph(800, 400);
        $graph->SetScale('textlin');
        
        // Set title
        $graph->title->Set($data['title']);
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 12);
        
        // Create plot
        $plot = new \BarPlot($data['values']);
        $plot->SetFillColor('#3b82f6');
        
        // Add value labels
        $plot->value->Show();
        $plot->value->SetFormat('%.0f');
        
        $graph->Add($plot);
        
        // Generate chart image
        $filename = 'temp_charts/pdf_chart_' . time() . '.png';
        $fullPath = public_path($filename);
        
        // Ensure directory exists
        $dir = dirname($fullPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $graph->Stroke($fullPath);
        
        return $filename;
    }
    
    public function generatePieChartForPdf(string $surveyId, string $fieldName, array $data): string
    {
        $graph = new \PieGraph(600, 400);
        $graph->title->Set($data['title']);
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 12);
        
        $p1 = new \PiePlot($data['values']);
        $p1->SetLegends($data['labels']);
        $p1->SetSliceColors(['#3b82f6', '#ef4444', '#10b981', '#f59e0b']);
        
        $graph->Add($p1);
        
        $filename = 'temp_charts/pdf_pie_chart_' . time() . '.png';
        $fullPath = public_path($filename);
        
        $dir = dirname($fullPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $graph->Stroke($fullPath);
        
        return $filename;
    }
}
```

### Multi-Engine PDF Component
```php
use Spipu\Html2Pdf\Html2Pdf;
use Spatie\LaravelPdf\Facades\Pdf as SpatiePdf;
use Modules\Limesurvey\Models\SurveyResponse;

class MultiEnginePdfComponent
{
    public function generatePdfWithCharts(array $chartData, string $engine = 'html2pdf'): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
    {
        switch ($engine) {
            case 'spatie':
                return $this->generateWithSpatie($chartData);
            case 'html2pdf':
            default:
                return $this->generateWithHtml2Pdf($chartData);
        }
    }
    
    private function generateWithHtml2Pdf(array $chartData): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $chartGenerator = new PdfChartGenerator();
        $chartImages = [];
        
        foreach ($chartData as $index => $data) {
            $imagePath = $chartGenerator->generateChartForPdf(
                $data['survey_id'],
                $data['field_name'],
                [
                    'title' => $data['title'],
                    'values' => $data['values']
                ]
            );
            $chartImages[$index] = $imagePath;
        }
        
        $html = $this->buildPdfHtml($chartData, $chartImages);
        
        $html2pdf = new Html2Pdf('L', 'A4', 'en');
        $html2pdf->setTestIsImage(true);
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($html);
        
        $filename = 'survey_report_' . date('Y-m-d') . '.pdf';
        $path = storage_path('app/reports/' . $filename);
        $html2pdf->output($path, 'F');
        
        // Clean up temporary images
        $this->cleanupTempImages($chartImages);
        
        return response()->download($path);
    }
    
    private function generateWithSpatie(array $chartData): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $chartGenerator = new PdfChartGenerator();
        $processedCharts = [];
        
        foreach ($chartData as $index => $data) {
            $imagePath = $chartGenerator->generateChartForPdf(
                $data['survey_id'],
                $data['field_name'],
                [
                    'title' => $data['title'],
                    'values' => $data['values']
                ]
            );
            
            $imageData = file_get_contents(public_path($imagePath));
            $base64Image = 'data:image/png;base64,' . base64_encode($imageData);
            
            $processedCharts[] = [
                'title' => $data['title'],
                'image_base64' => $base64Image,
                'data_table' => $data['data_table'] ?? []
            ];
        }
        
        return SpatiePdf::view('ui.pdf.survey-report', [
            'charts' => $processedCharts,
            'title' => 'Survey Report',
            'date' => now()
        ])
        ->format('a4')
        ->name('survey_report_' . date('Y-m-d') . '.pdf');
    }
    
    private function buildPdfHtml(array $chartData, array $chartImages): string
    {
        $html = '<page backtop="20mm" backbottom="20mm" backleft="15mm" backright="15mm">';
        $html .= '<h1 style="text-align: center; font-size: 18pt; margin-bottom: 20px;">Survey Report</h1>';
        $html .= '<p style="text-align: center; margin-bottom: 20px;">Generated on: ' . date('F j, Y \a\t g:i A') . '</p>';
        
        foreach ($chartData as $index => $data) {
            if (isset($chartImages[$index])) {
                $html .= '<div style="margin: 20px 0; page-break-inside: avoid;">';
                $html .= '<h2 style="font-size: 14pt; margin-bottom: 10px;">' . e($data['title']) . '</h2>';
                $html .= '<img src="' . public_path($chartImages[$index]) . '" style="width: 100%; height: auto; border: 1px solid #ddd;">';
                
                if (!empty($data['data_table'])) {
                    $html .= '<table style="width: 100%; border-collapse: collapse; margin-top: 10px;">';
                    $html .= '<thead><tr><th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Response</th><th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Count</th><th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Percentage</th></tr></thead>';
                    $html .= '<tbody>';
                    
                    foreach ($data['data_table'] as $row) {
                        $html .= '<tr>';
                        $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . e($row['label']) . '</td>';
                        $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . e($row['count']) . '</td>';
                        $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . e($row['percentage']) . '%</td>';
                        $html .= '</tr>';
                    }
                    
                    $html .= '</tbody></table>';
                }
                
                $html .= '</div>';
            }
        }
        
        $html .= '</page>';
        
        return $html;
    }
    
    private function cleanupTempImages(array $images): void
    {
        foreach ($images as $imagePath) {
            $fullPath = public_path($imagePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}
```

## Styling and Theming

### CSS for Chart Components
```css
.chart-container {
    position: relative;
    width: 100%;
    height: 400px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.chart-actions {
    margin-top: 1rem;
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.pdf-generator-form {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1.5rem;
    max-width: 600px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 1rem;
}

.form-actions {
    margin-top: 1.5rem;
    text-align: right;
}

.chart-section {
    margin: 2rem 0;
    page-break-inside: avoid;
}

.chart-placeholder {
    text-align: center;
    padding: 2rem;
    background: #f9fafb;
    border: 1px dashed #d1d5db;
    border-radius: 0.5rem;
    color: #6b7280;
}

.chart-data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
    font-size: 0.875rem;
}

.chart-data-table th,
.chart-data-table td {
    border: 1px solid #d1d5db;
    padding: 0.5rem;
    text-align: left;
}

.chart-data-table th {
    background-color: #f3f4f6;
    font-weight: 600;
}
```

### Responsive Design
```css
@media (max-width: 768px) {
    .chart-container {
        height: 300px;
        padding: 0.5rem;
    }
    
    .pdf-generator-form {
        padding: 1rem;
        margin: 0.5rem;
    }
    
    .chart-data-table {
        font-size: 0.75rem;
    }
    
    .chart-data-table th,
    .chart-data-table td {
        padding: 0.25rem;
    }
}

@media (max-width: 480px) {
    .chart-container {
        height: 250px;
    }
    
    .chart-actions {
        flex-direction: column;
    }
    
    .chart-actions > * {
        width: 100%;
    }
}
```

## Accessibility Features

### ARIA Attributes for Charts
```blade
<canvas 
    id="accessible-chart-{{ $chartId }}"
    role="img"
    aria-label="Chart showing survey responses for {{ $questionText }}"
    aria-describedby="chart-desc-{{ $chartId }}"
></canvas>

<div id="chart-desc-{{ $chartId }}" class="sr-only">
    Survey responses for "{{ $questionText }}". 
    @foreach($chartData['data_table'] as $row)
        {{ $row['label'] }}: {{ $row['count'] }} responses ({{ $row['percentage'] }}%).
    @endforeach
</div>
```

### Keyboard Navigation
```javascript
document.addEventListener('DOMContentLoaded', function() {
    const chartCanvases = document.querySelectorAll('.chart-container canvas');
    
    chartCanvases.forEach(canvas => {
        canvas.setAttribute('tabindex', '0');
        canvas.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                // Trigger chart export or other action
                exportChartToPng(canvas.id.replace('chart-canvas-', ''));
            }
        });
    });
});
```

## Integration with Other Modules

### Integration with Quaeris Module
```blade
{{-- Survey PDF Generation Interface --}}
<div class="survey-pdf-generator">
    <h2>Generate Survey PDF Report</h2>
    
    <x-ui::form wire:submit.prevent="generateSurveyPdf">
        <div class="form-group">
            <x-ui::label for="survey_pdf_template">PDF Template</x-ui::label>
            <x-ui::select 
                id="survey_pdf_template" 
                wire:model="selectedTemplate"
            >
                <option value="">Select a template</option>
                @foreach($surveyPdfTemplates as $template)
                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                @endforeach
            </x-ui::select>
        </div>
        
        <div class="form-group">
            <x-ui::label>Include Charts</x-ui::label>
            <x-ui::checkbox 
                wire:model="includeCharts"
                id="include_charts"
            />
            <x-ui::label for="include_charts">Show charts in report</x-ui::label>
        </div>
        
        <div class="form-group">
            <x-ui::label for="chart_engine">Chart Engine</x-ui::label>
            <x-ui::select 
                id="chart_engine" 
                wire:model="chartEngine"
            >
                <option value="chartjs">Chart.js</option>
                <option value="jpgraph">JpGraph</option>
            </x-ui::select>
        </div>
        
        <div class="form-actions">
            <x-ui::button type="submit" class="btn-primary">
                Generate Report
            </x-ui::button>
        </div>
    </x-ui::form>
</div>
```

### Integration with Chart Module
```blade
{{-- Chart Configuration Interface --}}
<div class="chart-configurator">
    <h3>Configure Chart</h3>
    
    <x-ui::form wire:submit.prevent="updateChartConfig">
        <div class="form-group">
            <x-ui::label for="chart_type">Chart Type</x-ui::label>
            <x-ui::select 
                id="chart_type" 
                wire:model="chart.type"
            >
                <option value="bar1">Vertical Bar</option>
                <option value="bar2">Vertical Bar (Styled)</option>
                <option value="bar3">Vertical Bar (Detailed)</option>
                <option value="horizbar1">Horizontal Bar</option>
                <option value="horizbar2">Horizontal Bar (Styled)</option>
                <option value="pie1">Pie Chart</option>
                <option value="pieAvg">Pie Chart with Average</option>
                <option value="line1">Line Chart</option>
                <option value="lineSubQuestion">Line Chart (Sub-questions)</option>
            </x-ui::select>
        </div>
        
        <div class="form-group">
            <x-ui::label for="chart_width">Width</x-ui::label>
            <x-ui::input 
                id="chart_width" 
                type="number" 
                wire:model="chart.width"
                min="400"
                max="1200"
            />
        </div>
        
        <div class="form-group">
            <x-ui::label for="chart_height">Height</x-ui::label>
            <x-ui::input 
                id="chart_height" 
                type="number" 
                wire:model="chart.height"
                min="200"
                max="800"
            />
        </div>
        
        <div class="form-group">
            <x-ui::label for="chart_color">Color</x-ui::label>
            <x-ui::input 
                id="chart_color" 
                type="color" 
                wire:model="chart.list_color"
            />
        </div>
        
        <div class="form-actions">
            <x-ui::button type="submit" class="btn-primary">
                Update Chart
            </x-ui::button>
        </div>
    </x-ui::form>
</div>
```

## Advanced PDF Features

### Custom PDF Templates with Dual Approach Support
```blade
{{-- Custom PDF Template with Advanced Features and Approach Selection --}}
<div class="pdf-template advanced" 
     style="font-family: Arial, sans-serif; max-width: 21cm; margin: 0 auto; background: white; padding: 2cm;">
    
    <header class="pdf-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.5em; margin: 0; color: #1f2937;">{{ $title }}</h1>
                <p style="margin: 0.5em 0 0; color: #6b7280;">Survey ID: {{ $surveyId }} | Data Approach: {{ $dataApproach }}</p>
            </div>
            <div style="text-align: right;">
                <p style="margin: 0;">Report generated on</p>
                <p style="font-size: 1.2em; font-weight: bold; margin: 0;">{{ $date->format('F j, Y') }}</p>
            </div>
        </div>
        <hr style="margin: 1em 0; border: none; border-top: 1px solid #e5e7eb;">
    </header>
    
    <main class="pdf-content">
        <section class="executive-summary" style="background: #f9fafb; padding: 1em; border-radius: 0.5em; margin-bottom: 2em;">
            <h2 style="font-size: 1.2em; margin-top: 0;">Executive Summary</h2>
            <p>Total responses: {{ $totalResponses }}</p>
            <p>Date range: {{ $dateRange }}</p>
            <p>Data approach: {{ $dataApproach }} (Dynamic Model: {{ $dataApproach === 'dynamic' ? 'Direct field access' : 'Normalized EAV' }})</p>
        </section>
        
        @foreach($charts as $chart)
            <section class="chart-section" style="margin-bottom: 2em;">
                <h2 style="font-size: 1.1em; margin-top: 0; color: #1f2937;">{{ $chart['title'] }}</h2>
                
                @if($chart['image_path'])
                    <div style="display: flex; align-items: flex-start; gap: 1em; margin: 1em 0;">
                        <img src="{{ public_path($chart['image_path']) }}" 
                             alt="{{ $chart['title'] }}"
                             style="width: 60%; height: auto; border: 1px solid #ddd; border-radius: 0.25em;">
                        
                        @if($chart['data_table'])
                            <div style="flex: 1;">
                                <h3 style="font-size: 1em; margin-top: 0;">Detailed Data</h3>
                                <table style="width: 100%; border-collapse: collapse; font-size: 0.9em;">
                                    <thead>
                                        <tr style="background-color: #f3f4f6;">
                                            <th style="border: 1px solid #d1d5db; padding: 0.5em; text-align: left;">Response</th>
                                            <th style="border: 1px solid #d1d5db; padding: 0.5em; text-align: right;">Count</th>
                                            <th style="border: 1px solid #d1d5db; padding: 0.5em; text-align: right;">%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($chart['data_table'] as $row)
                                            <tr>
                                                <td style="border: 1px solid #d1d5db; padding: 0.5em;">{{ $row['label'] }}</td>
                                                <td style="border: 1px solid #d1d5db; padding: 0.5em; text-align: right;">{{ $row['count'] }}</td>
                                                <td style="border: 1px solid #d1d5db; padding: 0.5em; text-align: right;">{{ $row['percentage'] }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="chart-placeholder" style="text-align: center; padding: 2em; background: #fef2f2; border: 1px dashed #fecaca; border-radius: 0.5em; color: #dc2626;">
                        <p>No data available for this chart</p>
                    </div>
                @endif
            </section>
        @endforeach
    </main>
    
    <footer class="pdf-footer" style="margin-top: 2em; padding-top: 1em; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 0.9em;">
        <p>Page <span class="page-number"></span> of <span class="total-pages"></span></p>
        <p>Generated by Laraxot Survey System | Data Approach: {{ $dataApproach }}</p>
    </footer>
</div>
```

### Backend Implementation for Dual Approach PDF Generation
```php
use Modules\Limesurvey\Models\SurveyResponse;
use Modules\Limesurvey\Models\SurveyFlipResponse;

class DualApproachPdfGenerator
{
    public function generatePdfWithCharts(array $chartData, string $surveyId, string $approach = 'dynamic', array $options = []): string
    {
        $chartGenerator = new JpGraphGenerator();
        $chartImages = [];
        
        foreach ($chartData as $index => $chart) {
            if ($approach === 'flip') {
                // Use SurveyFlipResponse (EAV approach)
                $imagePath = $chartGenerator->generateChartFromFlipData(
                    $chart,
                    $surveyId,
                    $chart['question_id'] ?? '',
                    $chart['title'] ?? 'Chart'
                );
            } else {
                // Use SurveyResponse (dynamic table approach)
                $imagePath = $chartGenerator->generateChartFromSurveyData(
                    $chart,
                    $surveyId,
                    $chart['field_name'] ?? '',
                    $chart['title'] ?? 'Chart'
                );
            }
            
            $chartImages[] = $imagePath;
        }
        
        $html = $this->buildPdfHtml($chartData, $chartImages, $approach);
        
        $html2pdf = new Html2Pdf('L', 'A4', 'en');
        $html2pdf->setTestIsImage(true);
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($html);
        
        $filename = 'survey_report_' . $surveyId . '_' . $approach . '_' . date('Y-m-d') . '.pdf';
        $path = storage_path('app/reports/' . $filename);
        $html2pdf->output($path, 'F');
        
        $this->cleanupTempImages($chartImages);
        
        return $path;
    }
    
    public function generateComparisonPdf(array $chartData, string $surveyId): string
    {
        $chartGenerator = new JpGraphGenerator();
        $chartImages = [];
        
        foreach ($chartData as $index => $chart) {
            // Generate charts using both approaches for comparison
            $imagePath = $chartGenerator->generateComparisonChart(
                $chart,
                $surveyId,
                $chart['question_id'] ?? '',
                $chart['field_name'] ?? '',
                $chart['title'] ?? 'Chart'
            );
            
            $chartImages[] = $imagePath;
        }
        
        $html = $this->buildComparisonPdfHtml($chartData, $chartImages);
        
        $html2pdf = new Html2Pdf('L', 'A4', 'en');
        $html2pdf->setTestIsImage(true);
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($html);
        
        $filename = 'survey_comparison_report_' . $surveyId . '_' . date('Y-m-d') . '.pdf';
        $path = storage_path('app/reports/' . $filename);
        $html2pdf->output($path, 'F');
        
        $this->cleanupTempImages($chartImages);
        
        return $path;
    }
    
    private function buildPdfHtml(array $chartData, array $chartImages, string $approach): string
    {
        $html = '<page backtop="20mm" backbottom="20mm" backleft="15mm" backright="15mm">';
        $html .= '<h1 style="text-align: center; font-size: 18pt; margin-bottom: 10px;">Survey Report</h1>';
        $html .= '<p style="text-align: center; margin-bottom: 10px;">Survey ID: ' . e($chartData[0]['survey_id'] ?? 'N/A') . ' | Data Approach: ' . ucfirst($approach) . '</p>';
        $html .= '<p style="text-align: center; margin-bottom: 20px;">Generated on: ' . date('F j, Y \a\t g:i A') . '</p>';
        
        foreach ($chartData as $index => $data) {
            if (isset($chartImages[$index])) {
                $html .= '<div style="margin: 20px 0; page-break-inside: avoid;">';
                $html .= '<h2 style="font-size: 14pt; margin-bottom: 10px;">' . e($data['title']) . '</h2>';
                $html .= '<img src="' . public_path($chartImages[$index]) . '" style="width: 100%; height: auto; border: 1px solid #ddd;">';
                
                if (!empty($data['data_table'])) {
                    $html .= '<table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.8em;">';
                    $html .= '<thead><tr style="background-color: #f3f4f6;"><th style="border: 1px solid #d1d5db; padding: 0.5em; text-align: left;">Response</th><th style="border: 1px solid #d1d5db; padding: 0.5em; text-align: right;">Count</th><th style="border: 1px solid #d1d5db; padding: 0.5em; text-align: right;">%</th></tr></thead>';
                    $html .= '<tbody>';
                    
                    foreach ($data['data_table'] as $row) {
                        $html .= '<tr>';
                        $html .= '<td style="border: 1px solid #d1d5db; padding: 0.5em;">' . e($row['label']) . '</td>';
                        $html .= '<td style="border: 1px solid #d1d5db; padding: 0.5em; text-align: right;">' . e($row['count']) . '</td>';
                        $html .= '<td style="border: 1px solid #d1d5db; padding: 0.5em; text-align: right;">' . e($row['percentage']) . '%</td>';
                        $html .= '</tr>';
                    }
                    
                    $html .= '</tbody></table>';
                }
                
                $html .= '</div>';
            }
        }
        
        $html .= '</page>';
        
        return $html;
    }
    
    private function buildComparisonPdfHtml(array $chartData, array $chartImages): string
    {
        $html = '<page backtop="20mm" backbottom="20mm" backleft="15mm" backright="15mm">';
        $html .= '<h1 style="text-align: center; font-size: 18pt; margin-bottom: 10px;">Survey Data Comparison Report</h1>';
        $html .= '<p style="text-align: center; margin-bottom: 10px;">Survey ID: ' . e($chartData[0]['survey_id'] ?? 'N/A') . '</p>';
        $html .= '<p style="text-align: center; margin-bottom: 20px;">Comparing Dynamic Model vs Flip Approach | Generated on: ' . date('F j, Y \a\t g:i A') . '</p>';
        
        foreach ($chartData as $index => $data) {
            if (isset($chartImages[$index])) {
                $html .= '<div style="margin: 20px 0; page-break-inside: avoid;">';
                $html .= '<h2 style="font-size: 14pt; margin-bottom: 10px;">' . e($data['title']) . '</h2>';
                $html .= '<img src="' . public_path($chartImages[$index]) . '" style="width: 100%; height: auto; border: 1px solid #ddd;">';
                $html .= '<p style="text-align: center; font-style: italic;">Comparison of Dynamic Model (blue) vs Flip Approach (red)</p>';
                $html .= '</div>';
            }
        }
        
        $html .= '</page>';
        
        return $html;
    }
}
```

## Performance Optimization

### Chart Caching Component
```php
use Illuminate\Support\Facades\Cache;

class CachedChartComponent
{
    public function getChartWithCache(string $surveyId, string $fieldName, array $options = []): array
    {
        $cacheKey = "ui_chart_{$surveyId}_{$fieldName}_" . md5(serialize($options));
        $ttl = now()->addMinutes(30); // Cache for 30 minutes
        
        return Cache::remember($cacheKey, $ttl, function() use ($surveyId, $fieldName, $options) {
            // Generate chart data using dynamic models
            return $this->generateChartData($surveyId, $fieldName, $options);
        });
    }
    
    private function generateChartData(string $surveyId, string $fieldName, array $options): array
    {
        use Modules\Limesurvey\Models\SurveyResponse;
        
        $query = SurveyResponse::getResponsesForSurvey($surveyId)
            ->select([
                DB::raw("{$fieldName} as answer"),
                DB::raw('COUNT(*) as count')
            ])
            ->whereNotNull($fieldName)
            ->groupBy($fieldName)
            ->orderBy('count', 'desc');
            
        if (isset($options['date_from'])) {
            $query->where('submitdate', '>=', $options['date_from']);
        }
        
        if (isset($options['date_to'])) {
            $query->where('submitdate', '<=', $options['date_to']);
        }
        
        if (isset($options['limit'])) {
            $query->limit($options['limit']);
        }
        
        $results = $query->get();
        
        $total = $results->sum('count');
        
        $dataTable = $results->map(function($item) use ($total) {
            return [
                'label' => $item->answer,
                'count' => $item->count,
                'percentage' => $total > 0 ? round(($item->count / $total) * 100, 2) : 0
            ];
        });
        
        return [
            'labels' => $results->pluck('answer')->toArray(),
            'values' => $results->pluck('count')->toArray(),
            'data_table' => $dataTable->toArray(),
            'total' => $total
        ];
    }
}
```

## Security Considerations
- **Input Validation**: Validate all chart configuration inputs
- **File Security**: Secure chart image file paths and access
- **XSS Prevention**: Sanitize all data before rendering
- **PDF Content**: Validate HTML content before PDF generation
- **Dynamic Model Access**: Validate survey IDs before accessing dynamic tables
- **CSRF Protection**: Implement CSRF protection for all forms

## Performance Optimization
1. **Caching**: Cache chart data and configurations
2. **Asynchronous Processing**: Generate large charts in background jobs
3. **Image Optimization**: Optimize chart images for size and quality
4. **Memory Management**: Monitor memory usage for large datasets
5. **Database Indexing**: Proper indexing for survey response tables

## Troubleshooting
Common issues and solutions:
- **Chart not displaying**: Check file permissions and paths
- **PDF generation failures**: Verify PDF library dependencies
- **Performance issues**: Implement proper caching and queuing
- **Font rendering**: Ensure proper font libraries are installed
- **Dynamic model access**: Validate survey IDs before accessing dynamic tables
- **Memory issues**: Monitor and adjust memory limits for large reports

## Best Practices
1. **Responsive Design**: Ensure components work on all device sizes
2. **Accessibility**: Implement proper ARIA attributes and keyboard navigation
3. **Performance**: Use caching and async processing for large datasets
4. **Security**: Validate all inputs and secure file paths
5. **Dynamic Models**: Always use dynamic models (SurveyResponse) for LimeSurvey data access
6. **Error Handling**: Implement comprehensive error handling
7. **Testing**: Test components with various data types and edge cases

## Related Modules
- [Chart Module](../Chart/docs/index.md) - Chart generation and data processing
- [Quaeris Module](../Quaeris/docs/index.md) - Survey management and question charts
- [LimeSurvey Module](../Limesurvey/docs/index.md) - Survey data access with dynamic models
- [Xot Module](../Xot/docs/index.md) - Base UI infrastructure and component patterns

## Statistical Analysis for Question Type Y

### Enhanced Statistical Widgets
For question type Y (Yes/No responses), the system provides enhanced statistical analysis capabilities:

```php
namespace Modules\Quaeris\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Limesurvey\Models\SurveyResponse;
use Modules\Quaeris\Models\QuestionChart;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartAnswersYTypeWidget extends XotBaseTableWidget
{
    public ?QuestionChart $record = null;

    public function getTableColumns(): array
    {
        if ($this->record && $this->record->question_type === 'Y') {
            return [
                TextColumn::make('_id')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('submitdate')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
                TextColumn::make('label'),
                TextColumn::make('value')->badge(),
                TextColumn::make('Y_count')->badge()->label('Yes Count'),
                TextColumn::make('N_count')->badge()->label('No Count'),
                TextColumn::make('answer'),
                TextColumn::make('answer_lang'),
                TextColumn::make('yes_percentage')
                    ->label('Yes %')
                    ->formatStateUsing(function ($record) {
                        if (isset($record->Y_count) && isset($record->total_count) && $record->total_count > 0) {
                            return round(($record->Y_count / $record->total_count) * 100, 2) . '%';
                        }
                        return 'N/A';
                    }),
            ];
        }
        
        // Fallback to default columns for non-Y types
        return parent::getTableColumns();
    }

    protected function getTableQuery(): Builder
    {
        if ($this->record && $this->record->question_type === 'Y') {
            $field_name = $this->record->field_name;
            $qid = $this->record->question;
            
            return SurveyResponse::getResponsesForSurvey($this->record->surveyId)
                ->withAnswersLabel($qid, $field_name)
                ->selectRaw("
                    submitdate,
                    {$field_name} as value,
                    SUM({$field_name} = 'Y') as Y_count,
                    SUM({$field_name} = 'N') as N_count,
                    COUNT(*) as total_count,
                    (SUM({$field_name} = 'Y') * 100.0 / COUNT(*)) as yes_percentage
                ")
                ->whereNotNull($field_name)
                ->groupBy('submitdate', $field_name)
                ->orderBy('submitdate', 'desc');
        }
        
        // Fallback to default query for non-Y types
        return parent::getTableQuery();
    }
}
```

### Statistical Chart Generation for Y Type Questions
For question type Y, specialized chart generation includes percentage calculations:

```php
class YTypeChartGenerator
{
    public function generateYTypeChart(string $surveyId, string $fieldName): array
    {
        $results = SurveyResponse::getResponsesForSurvey($surveyId)
            ->select([
                DB::raw("{$fieldName} as answer"),
                DB::raw('COUNT(*) as count')
            ])
            ->whereNotNull($fieldName)
            ->groupBy($fieldName)
            ->get();
        
        $total = $results->sum('count');
        
        $labels = [];
        $values = [];
        $percentages = [];
        
        foreach ($results as $result) {
            $labels[] = $result->answer;
            $values[] = $result->count;
            $percentages[] = $total > 0 ? round(($result->count / $total) * 100, 2) : 0;
        }
        
        // Calculate average for Y type (percentage of 'Y' responses)
        $yesCount = collect($results)->firstWhere('answer', 'Y')?->count ?? 0;
        $averagePercentage = $total > 0 ? round(($yesCount / $total) * 100, 2) : 0;
        
        return [
            'labels' => $labels,
            'values' => $values,
            'percentages' => $percentages,
            'total' => $total,
            'average_percentage' => $averagePercentage,
            'chart_type' => 'pie' // or 'bar' depending on preference
        ];
    }
    
    public function generateYTypeTrendChart(string $surveyId, string $fieldName, string $groupBy = 'date:Y-m'): array
    {
        $sqlGroupBy = $this->getSql($surveyId, $groupBy, 'name');
        
        $results = SurveyResponse::getResponsesForSurvey($surveyId)
            ->selectRaw("
                {$sqlGroupBy} as period,
                COUNT(*) as total,
                SUM(CASE WHEN {$fieldName} = 'Y' THEN 1 ELSE 0 END) as yes_count,
                SUM(CASE WHEN {$fieldName} = 'N' THEN 1 ELSE 0 END) as no_count,
                AVG(CASE WHEN {$fieldName} = 'Y' THEN 1 ELSE 0 END) * 100 as percentage
            ")
            ->whereNotNull($fieldName)
            ->groupBy(DB::raw($sqlGroupBy))
            ->orderBy('period')
            ->get();
        
        return [
            'labels' => $results->pluck('period')->toArray(),
            'values' => $results->pluck('percentage')->toArray(),
            'total_responses' => $results->pluck('total')->toArray(),
            'yes_counts' => $results->pluck('yes_count')->toArray(),
            'no_counts' => $results->pluck('no_count')->toArray(),
            'chart_type' => 'line'
        ];
    }
}
```

### UI Components for Y Type Statistics
Specialized UI components for displaying statistics of Y type questions:

```blade
{{-- Y Type Statistics Component --}}
<div class="y-type-statistics" x-data="yTypeStats({{ $surveyId }}, {{ $questionId }})">
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Responses</h3>
            <p class="stat-value" x-text="stats.total"></p>
        </div>
        
        <div class="stat-card">
            <h3>Yes Responses</h3>
            <p class="stat-value" x-text="stats.yes_count"></p>
            <p class="stat-subtext" x-text="`(${stats.yes_percentage}%)`"></p>
        </div>
        
        <div class="stat-card">
            <h3>No Responses</h3>
            <p class="stat-value" x-text="stats.no_count"></p>
            <p class="stat-subtext" x-text="`(${stats.no_percentage}%)`"></p>
        </div>
        
        <div class="stat-card">
            <h3>Average</h3>
            <p class="stat-value" x-text="`${stats.average_percentage}%`"></p>
            <p class="stat-subtext">Yes responses</p>
        </div>
    </div>
    
    <div class="chart-container">
        <canvas id="y-type-chart" width="400" height="200"></canvas>
    </div>
    
    <script>
        function yTypeStats(surveyId, questionId) {
            return {
                stats: {
                    total: 0,
                    yes_count: 0,
                    no_count: 0,
                    yes_percentage: 0,
                    no_percentage: 0,
                    average_percentage: 0
                },
                
                async init() {
                    const response = await fetch(`/api/stats/y-type/${surveyId}/${questionId}`);
                    const data = await response.json();
                    this.stats = data;
                    this.renderChart();
                },
                
                renderChart() {
                    const ctx = document.getElementById('y-type-chart').getContext('2d');
                    
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Yes', 'No'],
                            datasets: [{
                                data: [this.stats.yes_percentage, this.stats.no_percentage],
                                backgroundColor: [
                                    '#10B981',  // Green for Yes
                                    '#EF4444'   // Red for No
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                title: {
                                    display: true,
                                    text: 'Yes/No Distribution'
                                }
                            }
                        }
                    });
                }
            };
        }
    </script>
</div>
```

---

<!-- Merged from INDEX.md, which collided with this file on case-insensitive filesystems. -->

# Documentation Index

## actions

- [table-layout-toggle-1.md](./actions/table-layout-toggle-1.md)
- [table-layout-toggle.md](./actions/table-layout-toggle.md)

## architecture
---
title: UI Module — Documentazione
module: UI
type: index
status: approved
updated: "2026-07-28"
related:
  - ./README.md
  - ./PATTERNS.md
  - ./TROUBLESHOOTING.md
---

# UI — Indice Documentazione

> Mappa completa della knowledge base locale del modulo UI. Ultimo aggiornamento: 2026-07-28

## 🚀 Quick Navigation

- **[README](./README.md)** — Scopo, quick start, architettura Blade/Filament
- **[PATTERNS](./PATTERNS.md)** — Decisioni architetturali, workflow core, anti-pattern
- **[TROUBLESHOOTING](./TROUBLESHOOTING.md)** — Errori comuni e soluzioni

## 📊 Statistiche Documentazione

| Categoria | File | Note |
| --- | --- | --- |
| **Core Architecture** | 7 | Component registration, Filament resources, Blade patterns |
| **Blade Components** | 50+ | UI components, x-ui:: prefixed, Tailwind styled |
| **Filament Widgets** | 20+ | CalendarWidget, StatsOverviewWidget, ChartWidget, custom fields |
| **Enums & Patterns** | 5 | TableLayoutEnum, state management, form validations |
| **Charts & Visualization** | 6 | ChartJS integration, data labels plugin, export strategy |
| **Standards & Best Practices** | 7 | Accessibility, form standards, UI standards, performance |
| **Themes & Assets** | 6 | Tailwind compilation, asset management, custom themes |
| **HTML2PDF & Export** | 6 | PDF generation, styling, security, advanced usage |
| **Testing** | 1 | Pest testing guide, component testing |
| **Quality & Analysis** | 1 | UI module quality report |
| **Wiki & Conceptual** | 3+ | Concepts, overviews, sources |
| **Roadmap & Planning** | 30+ | Q4 roadmap, bottlenecks, component/form/theme systems |
| **Raw & Archive** | 160+ | Legacy docs, imports, deprecated patterns |
| **Root Documentation** | 600+ | Flat file archive (pre-consolidation) |
| **TOTALE** | **~900** | Modulo UI |

## 📚 Sezioni Principali

### 🎯 Core Architecture

Fondamenti della struttura modulo e filosofia.

- [README.md](./README.md) — Overview, quick start, stack tech
- [PATTERNS.md](./PATTERNS.md) — Decisioni architetturali e workflow
- [TROUBLESHOOTING.md](./TROUBLESHOOTING.md) — Errori comuni e soluzioni
- [architecture/component-registration.md](./architecture/component-registration.md) — Registrazione Blade components
- [architecture/filament-pages-structure.md](./architecture/filament-pages-structure.md) — Filament Pages
- [architecture/filament-resources-structure.md](./architecture/filament-resources-structure.md) — Filament Resources
- [architecture/structure.md](./architecture/structure.md) — Struttura modulo app/

---

### 🎨 Blade Components

50+ componenti Blade con prefisso `x-ui::ui.` (convention).

**Categorie** (raggruppate in PATTERNS.md):

- **Layout & Navigation** — Master layouts, navigation, headers, footers
  - [layouts/master.md](./layouts/master.md)
  - [blocks/navigation.md](./blocks/navigation.md)
  - [blocks/logo.md](./blocks/logo.md)

- **Form Components** (custom + Filament integration)
  - [components/address-field.md](./components/address-field.md) — Custom AddressField
  - [components/file-upload.md](./components/file-upload.md) — File upload widget
  - [components/inline-date-picker.md](./components/inline-date-picker.md) — Inline DatePicker
  - [components/opening-hours-field.md](./components/opening-hours-field.md) — Opening hours editor
  - [components/radio-collection.md](./components/radio-collection.md) — Radio group collection
  - [components/studio-card-selector.md](./components/studio-card-selector.md) — Studio selector

- **Data Display & Tables**
  - [components/table-columns.md](./components/table-columns.md) — Custom table columns
  - [components/ui-components.md](./components/ui-components.md) — Generic UI components

- **Full Calendar & Calendar Integration**
  - [components/full-calendar.md](./components/full-calendar.md)
  - [filament/best-practices.md](./filament/best-practices.md)

- **Other Components**
  - [blocks/user-dropdown.md](./blocks/user-dropdown.md)
  - [blocks/correct-filament-components.md](./blocks/correct-filament-components.md)

---

### 📱 Filament Widgets & Integrations

20+ widgets e componenti Filament, incluse custom form fields.

**Widgets Core** (da PATTERNS.md):

- **CalendarWidget** — Calendario interattivo con events
- **StatsOverviewWidget** — Panoramica statistiche (KPI, gauges)
- **ChartWidget** — Grafici interattivi (line, bar, pie)

**Custom Form Fields** (Filament-specific):

- [components/address-field.md](./components/address-field.md) — AddressField
- [components/file-upload.md](./components/file-upload.md) — FileUpload
- [components/inline-date-picker-component.md](./components/inline-date-picker-component.md) — InlineDatePicker
- [components/opening-hours-field.md](./components/opening-hours-field.md) — OpeningHoursField
- [components/radio-collection-component.md](./components/radio-collection-component.md) — RadioCollection
- [components/studio-card-selector-component.md](./components/studio-card-selector-component.md) — StudioCardSelector
- [components/table-columns.md](./components/table-columns.md) — TableColumns (custom columns)

**Filament Resources & Pages**:

- [filament/resources.md](./filament/resources.md) — Resource patterns
- [filament/list-records.md](./filament/list-records.md) — ListRecords pages
- [filament/nested-resource.md](./filament/nested-resource.md) — Nested resources
- [filament/wizard-best-practices.md](./filament/wizard-best-practices.md) — Form Wizard patterns
- [filament/wizard-step-naming.md](./filament/wizard-step-naming.md) — Naming conventions

**Filament Translation & Plugins**:

- [filament/automatic-translations.md](./filament/automatic-translations.md) — Translation loading
- [filament/label-translation-system.md](./filament/label-translation-system.md) — Label translations

**Filament Troubleshooting**:

- [filament/errors/common-errors.md](./filament/errors/common-errors.md)
- [filament/errors/dropdown-list-item-tag.md](./filament/errors/dropdown-list-item-tag.md)
- [filament/errors/static-instance-method-incompatibility.md](./filament/errors/static-instance-method-incompatibility.md)

---

### 🔤 Enums & Layout Patterns

Enum types per type-safety e layout toggle logic.

- **TableLayoutEnum** — Toggle list/grid layout
  - [archive/table-layout-enum-analysis.md](./archive/table-layout-enum-analysis.md)
  - [archive/table-layout-enum-usage.md](./archive/table-layout-enum-usage.md)
  - [archive/table-layout-enum-implementation-example.md](./archive/table-layout-enum-implementation-example.md)

- **Actions & State Management**:
  - [actions/table-layout-toggle.md](./actions/table-layout-toggle.md)
  - [archive/table-layout-toggle-1.md](./archive/table-layout-toggle-1.md)

---

### 📊 Charts & Visualization

ChartJS integration con data labels plugin.

- [charts/chartjs-datalabels-multiple-labels-complete-guide.md](./charts/chartjs-datalabels-multiple-labels-complete-guide.md) — Comprehensive guide
- [charts/chartjs-plugin-datalabels-filament5.md](./charts/chartjs-plugin-datalabels-filament5.md) — Filament 5 setup
- [charts/filament-chart-js-guide.md](./charts/filament-chart-js-guide.md) — Filament ChartJS integration
- [charts/export-strategy.md](./charts/export-strategy.md) — Chart export patterns
- [charts/server-side-actions.md](./charts/server-side-actions.md) — Server-side rendering
- [charts/shared-hosting-strategy.md](./charts/shared-hosting-strategy.md) — Shared hosting optimization

---

### 📐 Standards & Best Practices

Regole di qualità, accessibilità, form standards, performance.

- [standards/accessibility.md](./standards/accessibility.md) — WCAG compliance
- [standards/auth-form-standards.md](./standards/auth-form-standards.md) — Authentication form rules
- [standards/form-standards.md](./standards/form-standards.md) — General form standards
- [standards/performance.md](./standards/performance.md) — Performance optimization
- [standards/ui-standards.md](./standards/ui-standards.md) — UI component standards
- [filament/no-label-rule.md](./filament/no-label-rule.md) — No hardcoded labels rule
- [clean-code/no-obvious-comments.md](./clean-code/no-obvious-comments.md) — Code quality

---

### 🎭 Themes & Asset Management

Tailwind compilation, tema customization, asset pipeline.

- [themes/asset-management.md](./themes/asset-management.md) — Asset loading & compilation
- [themes/compilation.md](./themes/compilation.md) — Tailwind build process
- [themes/components.md](./themes/components.md) — Theme-specific components
- [themes/optimizations.md](./themes/optimizations.md) — Performance tweaks
- [themes/schemaless-attributes-guide.md](./themes/schemaless-attributes-guide.md) — Schema pattern
- [development/roadmap/theme-system.md](./development/roadmap/theme-system.md) — Theme roadmap

---

### 📄 HTML2PDF & Export

PDF generation, styling, security.

- [html2pdf/index.md](./html2pdf/index.md) — Panoramica
- [html2pdf/laravel.md](./html2pdf/laravel.md) — Laravel integration
- [html2pdf/styling.md](./html2pdf/styling.md) — CSS for PDF
- [html2pdf/usage.md](./html2pdf/usage.md) — Basic usage
- [html2pdf/advanced.md](./html2pdf/advanced.md) — Advanced patterns
- [html2pdf/security.md](./html2pdf/security.md) — Security considerations

---

### 🧪 Testing

Pest testing guide for UI components.

- [testing/pest-testing-guide.md](./testing/pest-testing-guide.md) — Component testing with Pest

---

### 🔍 Quality & Analysis

Quality reports e audit findings.

- [quality-analysis/ui-module-quality-report.md](./quality-analysis/ui-module-quality-report.md) — Module quality assessment

---

### 📚 Wiki, Concepts & Overviews

Architectural concepts, LLM wiki, memoria.

**Concepts**:

- [wiki/concepts/blade-component-registration.md](./wiki/concepts/blade-component-registration.md) — Component registration pattern
- [wiki/concepts/enum-select-usage.md](./wiki/concepts/enum-select-usage.md) — EnumSelect best practices
- [wiki/concepts/filament-first-blade-canonical.md](./wiki/concepts/filament-first-blade-canonical.md) — Filament-first approach
- [wiki/concepts/module-filament-component-autoload-rule.md](./wiki/concepts/module-filament-component-autoload-rule.md) — Component auto-registration
- [wiki/concepts/ui-operating-model.md](./wiki/concepts/ui-operating-model.md) — Operating model

**Overviews**:

- [wiki/overviews/ui-module.md](./wiki/overviews/ui-module.md) — Module overview
- [wiki/concepts/ui-services-support-to-actions.md](./wiki/concepts/ui-services-support-to-actions.md) — Services→Actions migration

**Memory & Sources**:

- [wiki/memories/lang-split-ui-claude-audit.md](./wiki/memories/lang-split-ui-claude-audit.md) — Lang split history
- [wiki/sources/ui-architecture-sources.md](./wiki/sources/ui-architecture-sources.md) — Source references

---

### 🗺️ Roadmap & Planning

Q4 roadmap, bottlenecks, component/form/theme systems.

**Roadmap Files**:

- [roadmap/00-overview.md](./roadmap/00-overview.md) — Overview
- [roadmap/01-current-state.md](./roadmap/01-current-state.md) — Current state assessment
- [roadmap/02-goals.md](./roadmap/02-goals.md) — Goals e milestones
- [roadmap/03-workstreams.md](./roadmap/03-workstreams.md) — Workstreams
- [roadmap/bottlenecks.md](./roadmap/bottlenecks.md) — Identified bottlenecks
- [roadmap/component-system.md](./roadmap/component-system.md) — Component system roadmap
- [roadmap/form-components.md](./roadmap/form-components.md) — Form component roadmap
- [roadmap/theme-system.md](./roadmap/theme-system.md) — Theme system roadmap
- [roadmap/quality.md](./roadmap/quality.md) — Quality initiatives
- [roadmap/2025-q4-roadmap.md](./roadmap/2025-q4-roadmap.md) — Q4 detailed plan

**Planning & Tasks**:

- [tasks/001-design-system-components.md](./tasks/001-design-system-components.md)
- [tasks/filament-v5-alignment.md](./tasks/filament-v5-alignment.md)
- [tasks/increase-test-coverage.md](./tasks/increase-test-coverage.md)
- [tasks/refactor-complex-components.md](./tasks/refactor-complex-components.md)
- [tasks/ui-cleanup-docs.md](./tasks/ui-cleanup-docs.md)

---

### 📦 Raw & Archive

Legacy documentation pre-consolidation (160+ files).

- [raw/index.md](./raw/index.md) — Raw import index
- [raw/root-import/](./raw/root-import/) — Root import collection (API, blocks, filament, themes, etc.)
- [archive/](./archive/) — Deprecated/superseded files

---

### 📄 Root Files (Pre-consolidation)

600+ flat files in docs root (legacy structure, migrating to organized categories).

Vedi cartelle sopra per nuova organizzazione canonica.

---

## 🏗️ Struttura Modulo

```
laravel/Modules/UI/
├── app/
│   ├── Actions/              # Azioni queued (TableLayoutToggle, etc.)
│   ├── Filament/
│   │   ├── Resources/        # Filament resources
│   │   ├── Pages/            # Custom pages
│   │   └── Widgets/          # CalendarWidget, StatsOverviewWidget, ChartWidget
│   ├── Models/               # UI-specific models (rarely needed)
│   └── Services/             # Services → Actions migration
├── resources/
│   ├── views/
│   │   ├── components/       # Blade components (x-ui::ui.*)
│   │   ├── filament/         # Filament-specific views
│   │   ├── layouts/          # Master layouts
│   │   └── blocks/           # Reusable blocks (nav, footer, etc.)
│   ├── css/                  # Tailwind custom styles
│   └── js/                   # Alpine.js, ChartJS, etc.
├── lang/
│   ├── it/                   # Italian translations
│   │   ├── forms.php         # Form labels
│   │   └── ui.php            # UI labels
│   └── en/                   # English translations (parallel structure)
├── tests/
│   ├── Feature/              # Filament resource tests
│   ├── Unit/                 # Component unit tests
│   └── Pest/                 # Pest test suite
└── docs/
    ├── INDEX.md              # This file
    ├── README.md             # Quick start
    ├── PATTERNS.md           # Architecture & decisions
    ├── TROUBLESHOOTING.md    # Common errors
    ├── architecture/         # Detailed architecture docs
    ├── components/           # Component docs
    ├── filament/             # Filament patterns
    ├── charts/               # ChartJS integration
    ├── themes/               # Theme customization
    ├── html2pdf/             # PDF generation
    ├── standards/            # Quality & accessibility
    ├── testing/              # Testing guides
    ├── roadmap/              # Planning docs
    └── wiki/                 # Conceptual knowledge
```

---

## 📋 Regole Fondamentali

### Blade Components

- **Prefixing**: Tutti i Blade components DEVONO usare prefisso `x-ui::ui.`
  ```blade
  <x-ui::ui.button :label="'Submit'" />
  ```

- **PHPDoc**: Ogni component ha `@param` PHPDoc su view
  ```php
  /**
   * @param string $label Button label text
   * @param string $color Tailwind color (primary, secondary, danger)
   * @param bool $disabled Disable button interaction
   */
  ```

- **Tailwind Utility Classes**: Nessun inline style; solo Tailwind utilities
  ```blade
  {{-- ✅ CORRETTO --}}
  <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
  
  {{-- ❌ SBAGLIATO --}}
  <div style="background-color: #f3f4f6; padding: 16px;">
  ```

### Filament Integration

- **No Hardcoded Labels**: Navigation e form labels DEVONO venire da translation files
  ```php
  // ✅ CORRETTO
  protected static ?string $navigationLabel = null; // Loads from lang file
  
  // ❌ SBAGLIATO
  protected static ?string $navigationLabel = 'Users';
  ```

- **PHPStan Level 10**: Tutti i file devono passare PHPStan L10
  ```bash
  phpstan analyse Modules/UI --level=max
  ```

- **Multilingua**: IT/EN translation files per ogni resource
  ```
  lang/it/users.php
  lang/en/users.php
  ```

### Enums & Type Safety

- **TableLayoutEnum**: Utilizzo per list/grid toggle
  ```php
  use Modules\UI\Enums\TableLayoutEnum;
  
  $layout = TableLayoutEnum::LIST; // Type-safe
  ```

- **State Enums**: Utilizzo Enums per state management (not strings)

### Asset Management

- **Tailwind Compilation**: Build CSS before deployment
  ```bash
  npm run build
  ```

- **No Inline Styles**: Tutti gli stili via Tailwind o `resources/css/`

### Testing

- **Pest Testing**: Utilizzo Pest per component/widget tests
- **No migrate:fresh**: Vedi [rules-testing-no-migrate-fresh.md](./rules-testing-no-migrate-fresh.md)

---

## 📖 Governance Notes

**Ownership**: Modulo UI gestisce tutti gli aspetti della UI/UX della piattaforma.

**File Management**: 
- Tutti i file in `docs/` sono managed dal modulo UI
- Consolidation in progress: vedi [PATTERNS.md](./PATTERNS.md) per nuovo structure
- Legacy files in `/archive` e `/raw` per reference storico

**Cross-Module Dependencies**:
- UI dipende da Filament, Blade, Tailwind (framework-level)
- UI NON dipende da business logic (Progressioni, Performance, Ptv)
- Dependency Injection per cross-module services

**Quality Gates**:
- PHPStan Level 10 su tutti i .php file
- Pest test coverage per components
- WCAG accessibility compliance su nuovi components
- Performance audit per ChartJS e heavy rendering

---

## 🔗 Riferimenti Correlati

- [README](./README.md) — Quick start e overview
- [PATTERNS](./PATTERNS.md) — Decisioni architetturali e anti-pattern
- [TROUBLESHOOTING](./TROUBLESHOOTING.md) — Errori comuni e soluzioni
- [Wiki Index](./wiki/index.md) — Conceptual knowledge base

---

**Last Updated**: 2026-07-28  
**Status**: Approved for consolidation  
**Next Steps**: Create PATTERNS.md and TROUBLESHOOTING.md with UI-specific content
# Documentation Index

# UI — Indice Documentazione

> Mappa completa della knowledge base locale del modulo UI. Ultimo aggiornamento: 2026-07-28

## architecture

- [component-registration.md](./architecture/component-registration.md)
- [filament-pages-structure.md](./architecture/filament-pages-structure.md)
- [filament-resources-structure.md](./architecture/filament-resources-structure.md)
- [structure.md](./architecture/structure.md)

## archive

- [advanced-form-components.md](./archive/advanced-form-components.md)
- [algolia-docsearch-1.md](./archive/algolia-docsearch-1.md)
- [algolia-docsearch.md](./archive/algolia-docsearch.md)
- [architecture-rules-1.md](./archive/architecture-rules-1.md)
- [architecture-rules-2.md](./archive/architecture-rules-2.md)
- [architecture-rules.md](./archive/architecture-rules.md)
- [auth-pages.md](./archive/auth-pages.md)
- [base-components.md](./archive/base-components.md)
- [best-practices-1.md](./archive/best-practices-1.md)
- [best-practices.md](./archive/best-practices.md)
- [blade-data-handling-1.md](./archive/blade-data-handling-1.md)
- [blade-data-handling.md](./archive/blade-data-handling.md)
- [blocks-system-1.md](./archive/blocks-system-1.md)
- [blocks-system.md](./archive/blocks-system.md)
- [bugfix-icons-missing-1.md](./archive/bugfix-icons-missing-1.md)
- [bugfix-icons-missing-2025-01-27.deprecated.md](./archive/bugfix-icons-missing-2025-01-27.deprecated.md)
- [bugfix-icons-missing.md](./archive/bugfix-icons-missing.md)
- [bugfix-table-layout-action-1.md](./archive/bugfix-table-layout-action-1.md)
- [bugfix-table-layout-action-2025-01-27.deprecated.md](./archive/bugfix-table-layout-action-2025-01-27.deprecated.md)
- [bugfix-table-layout-action.md](./archive/bugfix-table-layout-action.md)
- [chart-components-1.md](./archive/chart-components-1.md)
- [chart-components.md](./archive/chart-components.md)
- [cms-link-1.md](./archive/cms-link-1.md)
- [cms-link.md](./archive/cms-link.md)
- [cms-themes-link-1.md](./archive/cms-themes-link-1.md)
- [cms-themes-link.md](./archive/cms-themes-link.md)
- [components-guide-1.md](./archive/components-guide-1.md)
- [components-guide.md](./archive/components-guide.md)
- [conflict-resolution-iconstatecolumn.md](./archive/conflict-resolution-iconstatecolumn.md)
- [conflict-resolution-tablelayoutenum.md](./archive/conflict-resolution-tablelayoutenum.md)
- [conflict-resolution-translation-files.md](./archive/conflict-resolution-translation-files.md)
- [convenzioni-naming-campi-1.md](./archive/convenzioni-naming-campi-1.md)
- [convenzioni-naming-campi.md](./archive/convenzioni-naming-campi.md)
- [custom-404-page-1.md](./archive/custom-404-page-1.md)
- [custom-404-page.md](./archive/custom-404-page.md)
- [customizing-your-site-1.md](./archive/customizing-your-site-1.md)
- [customizing-your-site.md](./archive/customizing-your-site.md)
- [data-display-components.md](./archive/data-display-components.md)
- [design-system-1.md](./archive/design-system-1.md)
- [design-system.md](./archive/design-system.md)
- [feedback-components.md](./archive/feedback-components.md)
- [filament-4x-upgrade-report.md](./archive/filament-4x-upgrade-report.md)
- [filament-4x-upgrade.md](./archive/filament-4x-upgrade.md)
- [filament-blade-components-usage-1.md](./archive/filament-blade-components-usage-1.md)
- [filament-blade-components-usage-2.md](./archive/filament-blade-components-usage-2.md)
- [filament-blade-components-usage.md](./archive/filament-blade-components-usage.md)
- [filament-components-1.md](./archive/filament-components-1.md)
- [filament-components-errors-1.md](./archive/filament-components-errors-1.md)
- [filament-components-errors.md](./archive/filament-components-errors.md)
- [filament-components-location-studio-1.md](./archive/filament-components-location-studio-1.md)
- [filament-components-location-studio.md](./archive/filament-components-location-studio.md)
- [filament-components-usage-1.md](./archive/filament-components-usage-1.md)
- [filament-components-usage-2.md](./archive/filament-components-usage-2.md)
- [filament-components-usage.md](./archive/filament-components-usage.md)
- [filament-components.md](./archive/filament-components.md)
- [filament-dropdown-avatar-components.md](./archive/filament-dropdown-avatar-components.md)
- [filament-dropdown-avatar-usage-1.md](./archive/filament-dropdown-avatar-usage-1.md)
- [filament-dropdown-avatar-usage.md](./archive/filament-dropdown-avatar-usage.md)
- [filament-error-fileupload-buttonlabel-1.md](./archive/filament-error-fileupload-buttonlabel-1.md)
- [filament-error-fileupload-buttonlabel.md](./archive/filament-error-fileupload-buttonlabel.md)
- [filament-error-fileupload-icon-1.md](./archive/filament-error-fileupload-icon-1.md)
- [filament-error-fileupload-icon.md](./archive/filament-error-fileupload-icon.md)
- [filament-error-fileupload-prefixicon-1.md](./archive/filament-error-fileupload-prefixicon-1.md)
- [filament-error-fileupload-prefixicon.md](./archive/filament-error-fileupload-prefixicon.md)
- [filament-fileupload-1.md](./archive/filament-fileupload-1.md)
- [filament-fileupload-components-1.md](./archive/filament-fileupload-components-1.md)
- [filament-fileupload-components.md](./archive/filament-fileupload-components.md)
- [filament-fileupload.md](./archive/filament-fileupload.md)
- [filament-pages-refactoring.md](./archive/filament-pages-refactoring.md)
- [filament-resources-structure-1.md](./archive/filament-resources-structure-1.md)
- [filament-resources-structure.md](./archive/filament-resources-structure.md)
- [filament-v4-theme-upgrade.md](./archive/filament-v4-theme-upgrade.md)
- [filament-vscode-1.md](./archive/filament-vscode-1.md)
- [filament-vscode.md](./archive/filament-vscode.md)
- [flags-components-1.md](./archive/flags-components-1.md)
- [flags-components-2.md](./archive/flags-components-2.md)
- [flags-components.md](./archive/flags-components.md)
- [form-components-1.md](./archive/form-components-1.md)
- [form-components.md](./archive/form-components.md)
- [getting-started-1.md](./archive/getting-started-1.md)
- [getting-started.md](./archive/getting-started.md)
- [iconstatesplitcolumn-actions-implementation.md](./archive/iconstatesplitcolumn-actions-implementation.md)
- [iconstatesplitcolumn-implementation-1.md](./archive/iconstatesplitcolumn-implementation-1.md)
- [iconstatesplitcolumn-implementation.md](./archive/iconstatesplitcolumn-implementation.md)
- [inline-date-picker-1.md](./archive/inline-date-picker-1.md)
- [inline-date-picker.md](./archive/inline-date-picker.md)
- [italian-language-corrections.md](./archive/italian-language-corrections.md)
- [lang-link-1.md](./archive/lang-link-1.md)
- [lang-link.md](./archive/lang-link.md)
- [layout-components.md](./archive/layout-components.md)
- [layouts-and-themes-1.md](./archive/layouts-and-themes-1.md)
- [layouts-and-themes.md](./archive/layouts-and-themes.md)
- [mcp-integration-1.md](./archive/mcp-integration-1.md)
- [mcp-integration.md](./archive/mcp-integration.md)
- [mcp-server-recommended.md](./archive/mcp-server-recommended.md)
- [naming-conventions-1.md](./archive/naming-conventions-1.md)
- [naming-conventions.md](./archive/naming-conventions.md)
- [naming-rules-1.md](./archive/naming-rules-1.md)
- [naming-rules.md](./archive/naming-rules.md)
- [navigation-components-1.md](./archive/navigation-components-1.md)
- [navigation-components-2.md](./archive/navigation-components-2.md)
- [navigation-components.md](./archive/navigation-components.md)
- [never-use-label-rule.md](./archive/never-use-label-rule.md)
- [opening-hours-rule-localization.md](./archive/opening-hours-rule-localization.md)
- [opening-hours-translation-fix.md](./archive/opening-hours-translation-fix.md)
- [optimization-recommendations.md](./archive/optimization-recommendations.md)
- [paths-and-assets-1.md](./archive/paths-and-assets-1.md)
- [paths-and-assets-2.md](./archive/paths-and-assets-2.md)
- [paths-and-assets.md](./archive/paths-and-assets.md)
- [phpstan-corrections-summary.md](./archive/phpstan-corrections-summary.md)
- [phpstan-fixes-1.md](./archive/phpstan-fixes-1.md)
- [phpstan-fixes-2025.md](./archive/phpstan-fixes-2025.md)
- [phpstan-fixes.md](./archive/phpstan-fixes.md)
- [phpstan-level-10-cleanup.md](./archive/phpstan-level-10-cleanup.md)
- [phpstan-level-10-compliance.md](./archive/phpstan-level-10-compliance.md)
- [phpstan-radio-badge-fix-1.md](./archive/phpstan-radio-badge-fix-1.md)
- [phpstan-radio-badge-fix.md](./archive/phpstan-radio-badge-fix.md)
- [public-resources-management-1.md](./archive/public-resources-management-1.md)
- [public-resources-management-2.md](./archive/public-resources-management-2.md)
- [public-resources-management.md](./archive/public-resources-management.md)
- [radio-collection-component.md](./archive/radio-collection-component.md)
- [selectstatecolumn-confirmation-modal-1.md](./archive/selectstatecolumn-confirmation-modal-1.md)
- [selectstatecolumn-confirmation-modal.md](./archive/selectstatecolumn-confirmation-modal.md)
- [spatie-media-library-migration-1.md](./archive/spatie-media-library-migration-1.md)
- [spatie-media-library-migration.md](./archive/spatie-media-library-migration.md)
- [state-transitions.md](./archive/state-transitions.md)
- [strict-types-implementation.md](./archive/strict-types-implementation.md)
- [struttura-themes-folio-1.md](./archive/struttura-themes-folio-1.md)
- [struttura-themes-folio.md](./archive/struttura-themes-folio.md)
- [studio-card-selector-implementation-1.md](./archive/studio-card-selector-implementation-1.md)
- [studio-card-selector-implementation.md](./archive/studio-card-selector-implementation.md)
- [table-components-1.md](./archive/table-components-1.md)
- [table-components.md](./archive/table-components.md)
- [table-layout-enum-analysis.md](./archive/table-layout-enum-analysis.md)
- [table-layout-enum-implementation-example.md](./archive/table-layout-enum-implementation-example.md)
- [table-layout-enum-usage-1.md](./archive/table-layout-enum-usage-1.md)
- [table-layout-enum-usage.md](./archive/table-layout-enum-usage.md)
- [transclass-rule.md](./archive/transclass-rule.md)
- [validation-files-multilingua.md](./archive/validation-files-multilingua.md)
- [vscode-filament-extension.md](./archive/vscode-filament-extension.md)
- [vscode-filament-plugin-1.md](./archive/vscode-filament-plugin-1.md)
- [vscode-filament-plugin.md](./archive/vscode-filament-plugin.md)
- [vscode-php-setup.md](./archive/vscode-php-setup.md)
- [widget-optimization.md](./archive/widget-optimization.md)

## best-practices

- [naming-conventions.md](./best-practices/naming-conventions.md)

## blade

- [component-registration.md](./blade/component-registration.md)
- [filament-components.md](./blade/filament-components.md)

## blocks

- [correct-filament-components.md](./blocks/correct-filament-components.md)
- [filament-component-integration.md](./blocks/filament-component-integration.md)
- [logo.md](./blocks/logo.md)
- [navigation.md](./blocks/navigation.md)
- [user-dropdown.md](./blocks/user-dropdown.md)

## bugfix

- [groupcolumn-architectural-violations.md](./bugfix/groupcolumn-architectural-violations.md)
- [iconcolumn-extends-filament-column.md](./bugfix/iconcolumn-extends-filament-column.md)
- [iconcolumn-view-path-fix.md](./bugfix/iconcolumn-view-path-fix.md)

## charts

- [chartjs-datalabels-multiple-labels-complete-guide.md](./charts/chartjs-datalabels-multiple-labels-complete-guide.md)
- [chartjs-plugin-datalabels-filament5.md](./charts/chartjs-plugin-datalabels-filament5.md)
- [export-strategy.md](./charts/export-strategy.md)
- [filament-chart-js-guide.md](./charts/filament-chart-js-guide.md)
- [server-side-actions.md](./charts/server-side-actions.md)
- [shared-hosting-strategy.md](./charts/shared-hosting-strategy.md)

## clean-code

- [no-obvious-comments.md](./clean-code/no-obvious-comments.md)
- [syntax-error-fixes.md](./clean-code/syntax-error-fixes.md)
- [wizard-schema-aration.md](./clean-code/wizard-schema-aration.md)
- [wizard-schema-separation.md](./clean-code/wizard-schema-separation.md)
- [wizard-steps.md](./clean-code/wizard-steps.md)

## components

- [address-field-1.md](./components/address-field-1.md)
- [address-field.md](./components/address-field.md)
- [blade-component-registration.md](./components/blade-component-registration.md)
- [filament-usage.md](./components/filament-usage.md)
- [filament.md](./components/filament.md)
- [file-upload.md](./components/file-upload.md)
- [footer.md](./components/footer.md)
- [full-calendar-1.md](./components/full-calendar-1.md)
- [full-calendar.md](./components/full-calendar.md)
- [iconstatesplicolumn-improvements.md](./components/iconstatesplicolumn-improvements.md)
- [inline-date-picker-component.md](./components/inline-date-picker-component.md)
- [inline-date-picker.md](./components/inline-date-picker.md)
- [opening-hours-field.md](./components/opening-hours-field.md)
- [page-component-migration.md](./components/page-component-migration.md)
- [radio-card-selector-component.md](./components/radio-card-selector-component.md)
- [radio-collection-component.md](./components/radio-collection-component.md)
- [radio-collection-debugging.md](./components/radio-collection-debugging.md)
- [radio-collection-fix-summary.md](./components/radio-collection-fix-summary.md)
- [radio-collection-fix-sumy.md](./components/radio-collection-fix-sumy.md)
- [radio-collection-implementation.md](./components/radio-collection-implementation.md)
- [radio-collection-philosophy.md](./components/radio-collection-philosophy.md)
- [radio-collection-usage-examples.md](./components/radio-collection-usage-examples.md)
- [studio-card-selector-component.md](./components/studio-card-selector-component.md)
- [studio-selection-component.md](./components/studio-selection-component.md)
- [table-columns.md](./components/table-columns.md)
- [ui-components.md](./components/ui-components.md)
- [volt.md](./components/volt.md)

## components/archive

- [full-calendar-1.md](./components/archive/full-calendar-1.md)
- [full-calendar.md](./components/archive/full-calendar.md)

## components/legacy

- [full-calendar-1.md](./components/legacy/full-calendar-1.md)
- [full-calendar.md](./components/legacy/full-calendar.md)

## components/ui_components

- [full-calendar.md](./components/ui_components/full-calendar.md)

## core

- [architecture.md](./core/architecture.md)

## development

- [roadmap.md](./development/roadmap.md)

## development/roadmap

- [bottlenecks.md](./development/roadmap/bottlenecks.md)
- [component-system.md](./development/roadmap/component-system.md)
- [form-component.md](./development/roadmap/form-component.md)
- [form-components.md](./development/roadmap/form-components.md)
- [theme-system.md](./development/roadmap/theme-system.md)

## examples

- [inline-date-picker-usage.md](./examples/inline-date-picker-usage.md)
- [table-layout-implementation-example.md](./examples/table-layout-implementation-example.md)

## filament

- [automatic-translations.md](./filament/automatic-translations.md)
- [best-practices.md](./filament/best-practices.md)
- [component-icon-support.md](./filament/component-icon-support.md)
- [component-methods-compatibility.md](./filament/component-methods-compatibility.md)
- [filament-4-components-guide.md](./filament/filament-4-components-guide.md)
- [filament-4-migration-guide.md](./filament/filament-4-migration-guide.md)
- [filament-4-migration-summary.md](./filament/filament-4-migration-summary.md)
- [filament-4-migration-sumy.md](./filament/filament-4-migration-sumy.md)
- [file-upload-component.md](./filament/file-upload-component.md)
- [installation.md](./filament/installation.md)
- [label-translation-system.md](./filament/label-translation-system.md)
- [list-records.md](./filament/list-records.md)
- [listrecords-1.md](./filament/listrecords-1.md)
- [listrecords.md](./filament/listrecords.md)
- [modules.md](./filament/modules.md)
- [nested-resource.md](./filament/nested-resource.md)
- [no-label-rule.md](./filament/no-label-rule.md)
- [pulse.md](./filament/pulse.md)
- [resource.md](./filament/resource.md)
- [resources.md](./filament/resources.md)
- [theme.md](./filament/theme.md)
- [vendor.md](./filament/vendor.md)
- [wizard-best-practices.md](./filament/wizard-best-practices.md)
- [wizard-step-naming.md](./filament/wizard-step-naming.md)

## filament-components

- [file-upload.md](./filament-components/file-upload.md)

## filament/actions

- [attach.md](./filament/actions/attach.md)
- [pdf.md](./filament/actions/pdf.md)

## filament/archive

- [listrecords-1.md](./filament/archive/listrecords-1.md)
- [listrecords.md](./filament/archive/listrecords.md)

## filament/errors

- [common-errors.md](./filament/errors/common-errors.md)
- [dropdown-list-item-tag.md](./filament/errors/dropdown-list-item-tag.md)
- [static-instance-method-incompatibility.md](./filament/errors/static-instance-method-incompatibility.md)

## html2pdf

- [advanced.md](./html2pdf/advanced.md)
- [index.md](./html2pdf/index.md)
- [laravel.md](./html2pdf/laravel.md)
- [security.md](./html2pdf/security.md)
- [styling.md](./html2pdf/styling.md)
- [usage.md](./html2pdf/usage.md)

## icons

- [icon-system.md](./icons/icon-system.md)

## layouts

- [master.md](./layouts/master.md)

## legacy

- [architecture-rules-1.md](./legacy/architecture-rules-1.md)
- [architecture-rules.md](./legacy/architecture-rules.md)
- [bugfix-table-layout-action.md](./legacy/bugfix-table-layout-action.md)
- [filament-components-usage-1.md](./legacy/filament-components-usage-1.md)
- [filament-components-usage.md](./legacy/filament-components-usage.md)
- [filament-dropdown-avatar-usage.md](./legacy/filament-dropdown-avatar-usage.md)
- [flags-components-1.md](./legacy/flags-components-1.md)
- [flags-components.md](./legacy/flags-components.md)
- [mcp-integration.md](./legacy/mcp-integration.md)
- [paths-and-assets-1.md](./legacy/paths-and-assets-1.md)
- [paths-and-assets.md](./legacy/paths-and-assets.md)

## llm-wiki

- [agents.md](./llm-wiki/AGENTS.md)
- [index.md](./llm-wiki/index.md)
- [log.md](./llm-wiki/log.md)

## quality-analysis

- [ui-module-quality-report.md](./quality-analysis/ui-module-quality-report.md)

## raw

- [index.md](./raw/index.md)

## raw/root-import

- [api-1.md](./raw/root-import/api-1.md)
- [api.md](./raw/root-import/api.md)
- [blocks-1.md](./raw/root-import/blocks-1.md)
- [blocks.md](./raw/root-import/blocks.md)
- [carousel-slider-1.md](./raw/root-import/carousel-slider-1.md)
- [carousel-slider.md](./raw/root-import/carousel-slider.md)
- [changelog-1.md](./raw/root-import/changelog-1.md)
- [changelog-2.md](./raw/root-import/changelog-2.md)
- [changelog.md](./raw/root-import/changelog.md)
- [chunk-1.md](./raw/root-import/chunk-1.md)
- [chunk.md](./raw/root-import/chunk.md)
- [ci-1.md](./raw/root-import/ci-1.md)
- [ci.md](./raw/root-import/ci.md)
- [custom-firm-fields-1.md](./raw/root-import/custom-firm-fields-1.md)
- [custom-firm-fields.md](./raw/root-import/custom-firm-fields.md)
- [custom-theme-1.md](./raw/root-import/custom-theme-1.md)
- [custom-theme.md](./raw/root-import/custom-theme.md)
- [eav-1.md](./raw/root-import/eav-1.md)
- [eav.md](./raw/root-import/eav.md)
- [effetcts-1.md](./raw/root-import/effetcts-1.md)
- [effetcts.md](./raw/root-import/effetcts.md)
- [filament-1.md](./raw/root-import/filament-1.md)
- [filament.md](./raw/root-import/filament.md)
- [flip-cards-1.md](./raw/root-import/flip-cards-1.md)
- [flip-cards.md](./raw/root-import/flip-cards.md)
- [global-search-1.md](./raw/root-import/global-search-1.md)
- [global-search.md](./raw/root-import/global-search.md)
- [links-1.md](./raw/root-import/links-1.md)
- [links.md](./raw/root-import/links.md)
- [media-1.md](./raw/root-import/media-1.md)
- [media.md](./raw/root-import/media.md)
- [megamenu-1.md](./raw/root-import/megamenu-1.md)
- [megamenu.md](./raw/root-import/megamenu.md)
- [navbar-1.md](./raw/root-import/navbar-1.md)
- [navbar.md](./raw/root-import/navbar.md)
- [page-builder-1.md](./raw/root-import/page-builder-1.md)
- [page-builder.md](./raw/root-import/page-builder.md)
- [qrcode-1.md](./raw/root-import/qrcode-1.md)
- [qrcode.md](./raw/root-import/qrcode.md)
- [ratings-1.md](./raw/root-import/ratings-1.md)
- [ratings.md](./raw/root-import/ratings.md)
- [tailwind-themes-1.md](./raw/root-import/tailwind-themes-1.md)
- [tailwind-themes.md](./raw/root-import/tailwind-themes.md)
- [test-1.md](./raw/root-import/test-1.md)
- [test.md](./raw/root-import/test.md)
- [theme-1.md](./raw/root-import/theme-1.md)
- [theme.md](./raw/root-import/theme.md)
- [ubuntu-1.md](./raw/root-import/ubuntu-1.md)
- [ubuntu.md](./raw/root-import/ubuntu.md)
- [widgets-1.md](./raw/root-import/widgets-1.md)
- [widgets.md](./raw/root-import/widgets.md)

## roadmap

- [00-index-1.md](./roadmap/00-index-1.md)
- [00-index.md](./roadmap/00-index.md)
- [00-overview.md](./roadmap/00-overview.md)
- [01-current-state.md](./roadmap/01-current-state.md)
- [01-now.md](./roadmap/01-now.md)
- [02-goals.md](./roadmap/02-goals.md)
- [02-next.md](./roadmap/02-next.md)
- [03-later.md](./roadmap/03-later.md)
- [03-workstreams.md](./roadmap/03-workstreams.md)
- [04-milestones.md](./roadmap/04-milestones.md)
- [04-risks.md](./roadmap/04-risks.md)
- [05-risks.md](./roadmap/05-risks.md)
- [2025-q4-roadmap.md](./roadmap/2025-q4-roadmap.md)
- [bottlenecks.md](./roadmap/bottlenecks.md)
- [component-system-1.md](./roadmap/component-system-1.md)
- [component-system.md](./roadmap/component-system.md)
- [form-component-1.md](./roadmap/form-component-1.md)
- [form-component.md](./roadmap/form-component.md)
- [form-components.md](./roadmap/form-components.md)
- [legacy-roadmap.md](./roadmap/legacy-roadmap.md)
- [phases.md](./roadmap/phases.md)
- [q4-roadmap.md](./roadmap/q4-roadmap.md)
- [quality.md](./roadmap/quality.md)
- [roadmap-q4.md](./roadmap/roadmap-q4.md)
- [roadmap.md](./roadmap/roadmap.md)
- [theme-system-1.md](./roadmap/theme-system-1.md)
- [theme-system.md](./roadmap/theme-system.md)
- [vision.md](./roadmap/vision.md)

## roadmap/archive

- [component-system-1.md](./roadmap/archive/component-system-1.md)
- [component-system.md](./roadmap/archive/component-system.md)
- [form-component-1.md](./roadmap/archive/form-component-1.md)
- [form-component.md](./roadmap/archive/form-component.md)
- [theme-system-1.md](./roadmap/archive/theme-system-1.md)
- [theme-system.md](./roadmap/archive/theme-system.md)

## roadmap/legacy

- [legacy-roadmap-and-issues.md](./roadmap/legacy/legacy-roadmap-and-issues.md)
- [legacy-roadmap-conflict.md](./roadmap/legacy/legacy-roadmap-conflict.md)
- [legacy-roadmap.md](./roadmap/legacy/legacy-roadmap.md)

## roadmap/prior_cycles

- [component-system-1.md](./roadmap/prior_cycles/component-system-1.md)
- [component-system.md](./roadmap/prior_cycles/component-system.md)
- [form-component-1.md](./roadmap/prior_cycles/form-component-1.md)
- [form-component.md](./roadmap/prior_cycles/form-component.md)
- [theme-system-1.md](./roadmap/prior_cycles/theme-system-1.md)
- [theme-system.md](./roadmap/prior_cycles/theme-system.md)

## root

- [00-index-1.md](./00-index-1.md)
- [00-index.md](./00-index.md)
- [04-datas.md](./04-datas.md)
- [advanced-form-components-1.md](./advanced-form-components-1.md)
- [advanced-form-components.md](./advanced-form-components.md)
- [agent-confidence-discipline.md](./agent-confidence-discipline.md)
- [agent-confidence-protocol.md](./agent-confidence-protocol.md)
- [agent-edit-discipline.md](./agent-edit-discipline.md)
- [ai-methodologies.md](./ai-methodologies.md)
- [algolia-docsearch-1-1.md](./algolia-docsearch-1-1.md)
- [algolia-docsearch-1.md](./algolia-docsearch-1.md)
- [algolia-docsearch-2.md](./algolia-docsearch-2.md)
- [algolia-docsearch.md](./algolia-docsearch.md)
- [altro.md](./altro.md)
- [analysis.md](./analysis.md)
- [api.md](./api.md)
- [architecture-.md](./architecture-.md)
- [architecture-1.md](./architecture-1.md)
- [architecture-2025-1.md](./architecture-2025-1.md)
- [architecture-2025.md](./architecture-2025.md)
- [architecture-archive-1.md](./architecture-archive-1.md)
- [architecture-archive-2.md](./architecture-archive-2.md)
- [architecture-patterns.md](./architecture-patterns.md)
- [architecture-rules-1-1.md](./architecture-rules-1-1.md)
- [architecture-rules-1.md](./architecture-rules-1.md)
- [architecture-rules-2.md](./architecture-rules-2.md)
- [architecture-rules.md](./architecture-rules.md)
- [architecture.md](./architecture.md)
- [audit-columns-laraxot-compliance.md](./audit-columns-laraxot-compliance.md)
- [audit-models-migrations.md](./audit-models-migrations.md)
- [auth-pages-1.md](./auth-pages-1.md)
- [auth-pages.md](./auth-pages.md)
- [authentication.md](./authentication.md)
- [awstest-bugfix-undefined-variable.md](./awstest-bugfix-undefined-variable.md)
- [base-components-1.md](./base-components-1.md)
- [base-components.md](./base-components.md)
- [best-practices-1-1.md](./best-practices-1-1.md)
- [best-practices-1.md](./best-practices-1.md)
- [best-practices-2.md](./best-practices-2.md)
- [best-practices.md](./best-practices.md)
- [blade-components.md](./blade-components.md)
- [blade-data-handling-1-1.md](./blade-data-handling-1-1.md)
- [blade-data-handling-1.md](./blade-data-handling-1.md)
- [blade-data-handling-2.md](./blade-data-handling-2.md)
- [blade-data-handling.md](./blade-data-handling.md)
- [blade-icons.md](./blade-icons.md)
- [blocks-system-1-1.md](./blocks-system-1-1.md)
- [blocks-system-1.md](./blocks-system-1.md)
- [blocks-system-2.md](./blocks-system-2.md)
- [blocks-system.md](./blocks-system.md)
- [blocks.md](./blocks.md)
- [bottlenecks.md](./bottlenecks.md)
- [brands-icons-integration.md](./brands-icons-integration.md)
- [brands-icons.md](./brands-icons.md)
- [bugfix-address-field-hydration.md](./bugfix-address-field-hydration.md)
- [bugfix-awstest-undefined-variable.md](./bugfix-awstest-undefined-variable.md)
- [bugfix-icons-missing-.md](./bugfix-icons-missing-.md)
- [bugfix-icons-missing-1.md](./bugfix-icons-missing-1.md)
- [bugfix-icons-missing-2025-01-27.deprecated.md](./bugfix-icons-missing-2025-01-27.deprecated.md)
- [bugfix-icons-missing.md](./bugfix-icons-missing.md)
- [bugfix-table-layout-action-.md](./bugfix-table-layout-action-.md)
- [bugfix-table-layout-action-1.md](./bugfix-table-layout-action-1.md)
- [bugfix-table-layout-action-2025-01-27.deprecated.md](./bugfix-table-layout-action-2025-01-27.deprecated.md)
- [bugfix-table-layout-action-conflict.md](./bugfix-table-layout-action-conflict.md)
- [bugfix-table-layout-action.md](./bugfix-table-layout-action.md)
- [bugfix-table-layout-toggle-not-working.md](./bugfix-table-layout-toggle-not-working.md)
- [carousel-slider.md](./carousel-slider.md)
- [case-conflicts.md](./case-conflicts.md)
- [changelog.md](./CHANGELOG.md)
- [chart-components-1-1.md](./chart-components-1-1.md)
- [chart-components-1.md](./chart-components-1.md)
- [chart-components-2.md](./chart-components-2.md)
- [chart-components.md](./chart-components.md)
- [chunk.md](./chunk.md)
- [ci.md](./ci.md)
- [cms-link-1-1.md](./cms-link-1-1.md)
- [cms-link-1.md](./cms-link-1.md)
- [cms-link-2.md](./cms-link-2.md)
- [cms-link.md](./cms-link.md)
- [cms-themes-link-1-1.md](./cms-themes-link-1-1.md)
- [cms-themes-link-1.md](./cms-themes-link-1.md)
- [cms-themes-link-2.md](./cms-themes-link-2.md)
- [cms-themes-link.md](./cms-themes-link.md)
- [code-quality-analysis.md](./code-quality-analysis.md)
- [code-redundancy-audit.md](./code-redundancy-audit.md)
- [codex-error-fix.md](./codex-error-fix.md)
- [components-guide-1.md](./components-guide-1.md)
- [components-guide.md](./components-guide.md)
- [components.md](./components.md)
- [confidence-guidelines.md](./confidence-guidelines.md)
- [conflict-resolution-iconstatecolumn-1.md](./conflict-resolution-iconstatecolumn-1.md)
- [conflict-resolution-iconstatecolumn.md](./conflict-resolution-iconstatecolumn.md)
- [conflict-resolution-tablelayoutenum-1.md](./conflict-resolution-tablelayoutenum-1.md)
- [conflict-resolution-tablelayoutenum.md](./conflict-resolution-tablelayoutenum.md)
- [conflict-resolution-translation-files-1.md](./conflict-resolution-translation-files-1.md)
- [conflict-resolution-translation-files.md](./conflict-resolution-translation-files.md)
- [conflict-resolution.md](./conflict-resolution.md)
- [conflicts.md](./conflicts.md)
- [conflitti-merge-risolti-1.md](./conflitti-merge-risolti-1.md)
- [conflitti-merge-risolti.md](./conflitti-merge-risolti.md)
- [consolidation-plan.md](./consolidation-plan.md)
- [consolidation-script.md](./consolidation-script.md)
- [contracts-naming.md](./contracts-naming.md)
- [convenzioni-naming-campi-1-1.md](./convenzioni-naming-campi-1-1.md)
- [convenzioni-naming-campi-1.md](./convenzioni-naming-campi-1.md)
- [convenzioni-naming-campi-2.md](./convenzioni-naming-campi-2.md)
- [convenzioni-naming-campi.md](./convenzioni-naming-campi.md)
- [copilot-redundancy-audit-1.md](./copilot-redundancy-audit-1.md)
- [copilot-redundancy-audit-2026-05-25.deprecated.md](./copilot-redundancy-audit-2026-05-25.deprecated.md)
- [copilot-redundancy-audit.md](./copilot-redundancy-audit.md)
- [coverage.md](./coverage.md)
- [custom-404-page-1-1.md](./custom-404-page-1-1.md)
- [custom-404-page-1.md](./custom-404-page-1.md)
- [custom-404-page-2.md](./custom-404-page-2.md)
- [custom-404-page.md](./custom-404-page.md)
- [custom-firm-fields.md](./custom-firm-fields.md)
- [custom-theme.md](./custom-theme.md)
- [customizing-your-site-1-1.md](./customizing-your-site-1-1.md)
- [customizing-your-site-1.md](./customizing-your-site-1.md)
- [customizing-your-site-2.md](./customizing-your-site-2.md)
- [customizing-your-site.md](./customizing-your-site.md)
- [cyclomatic-complexity-report.md](./cyclomatic-complexity-report.md)
- [data-display-components-1.md](./data-display-components-1.md)
- [data-display-components.md](./data-display-components.md)
- [datas-not-dtos-convention.md](./datas-not-dtos-convention.md)
- [dependencies.md](./dependencies.md)
- [dependency-intelligence.md](./dependency-intelligence.md)
- [dependency-rules.md](./dependency-rules.md)
- [design-comuni-faq-components.md](./design-comuni-faq-components.md)
- [design-comuni-implementation.md](./design-comuni-implementation.md)
- [design-system-1-1.md](./design-system-1-1.md)
- [design-system-1.md](./design-system-1.md)
- [design-system-2.md](./design-system-2.md)
- [design-system.md](./design-system.md)
- [development-workflow-rules.md](./development-workflow-rules.md)
- [disabled-components.md](./disabled-components.md)
- [docs-archive-policy.md](./docs-archive-policy.md)
- [docs-health.md](./docs-health.md)
- [dry-kiss-analysis-.md](./dry-kiss-analysis-.md)
- [dry-kiss-analysis-1.md](./dry-kiss-analysis-1.md)
- [dry-kiss-analysis-2025-10-15.deprecated.md](./dry-kiss-analysis-2025-10-15.deprecated.md)
- [dry-kiss-analysis-conflict.md](./dry-kiss-analysis-conflict.md)
- [dry-kiss-analysis.md](./dry-kiss-analysis.md)
- [duplicate-methods-analysis.md](./duplicate-methods-analysis.md)
- [duplicate-methods-report.md](./duplicate-methods-report.md)
- [duplicate-methods.md](./duplicate-methods.md)
- [eav.md](./eav.md)
- [effetcts.md](./effetcts.md)
- [eloquent-isset-vs-property-exists.md](./eloquent-isset-vs-property-exists.md)
- [eloquent-properties-isset-vs-property-exists.md](./eloquent-properties-isset-vs-property-exists.md)
- [enum-transclass-implementation.md](./enum-transclass-implementation.md)
- [feedback-components-1.md](./feedback-components-1.md)
- [feedback-components.md](./feedback-components.md)
- [filament-4x-upgrade-1.md](./filament-4x-upgrade-1.md)
- [filament-4x-upgrade-report-1.md](./filament-4x-upgrade-report-1.md)
- [filament-4x-upgrade-report.md](./filament-4x-upgrade-report.md)
- [filament-4x-upgrade.md](./filament-4x-upgrade.md)
- [filament-5x-compatibility.md](./filament-5x-compatibility.md)
- [filament-blade-components-usage-1-1.md](./filament-blade-components-usage-1-1.md)
- [filament-blade-components-usage-1.md](./filament-blade-components-usage-1.md)
- [filament-blade-components-usage-2.md](./filament-blade-components-usage-2.md)
- [filament-blade-components-usage.md](./filament-blade-components-usage.md)
- [filament-components-1-1.md](./filament-components-1-1.md)
- [filament-components-1.md](./filament-components-1.md)
- [filament-components-2.md](./filament-components-2.md)
- [filament-components-errors-1-1.md](./filament-components-errors-1-1.md)
- [filament-components-errors-1.md](./filament-components-errors-1.md)
- [filament-components-errors-2.md](./filament-components-errors-2.md)
- [filament-components-errors.md](./filament-components-errors.md)
- [filament-components-location-studio-1-1.md](./filament-components-location-studio-1-1.md)
- [filament-components-location-studio-1.md](./filament-components-location-studio-1.md)
- [filament-components-location-studio-2.md](./filament-components-location-studio-2.md)
- [filament-components-location-studio.md](./filament-components-location-studio.md)
- [filament-components-usage-1-1.md](./filament-components-usage-1-1.md)
- [filament-components-usage-1.md](./filament-components-usage-1.md)
- [filament-components-usage-2.md](./filament-components-usage-2.md)
- [filament-components-usage.md](./filament-components-usage.md)
- [filament-components.md](./filament-components.md)
- [filament-custom-columns-relationship-resolution.md](./filament-custom-columns-relationship-resolution.md)
- [filament-dropdown-avatar-components-1.md](./filament-dropdown-avatar-components-1.md)
- [filament-dropdown-avatar-components.md](./filament-dropdown-avatar-components.md)
- [filament-dropdown-avatar-usage-1-1.md](./filament-dropdown-avatar-usage-1-1.md)
- [filament-dropdown-avatar-usage-1.md](./filament-dropdown-avatar-usage-1.md)
- [filament-dropdown-avatar-usage-2.md](./filament-dropdown-avatar-usage-2.md)
- [filament-dropdown-avatar-usage.md](./filament-dropdown-avatar-usage.md)
- [filament-error-fileupload-buttonlabel-1-1.md](./filament-error-fileupload-buttonlabel-1-1.md)
- [filament-error-fileupload-buttonlabel-1.md](./filament-error-fileupload-buttonlabel-1.md)
- [filament-error-fileupload-buttonlabel-2.md](./filament-error-fileupload-buttonlabel-2.md)
- [filament-error-fileupload-buttonlabel.md](./filament-error-fileupload-buttonlabel.md)
- [filament-error-fileupload-icon-1-1.md](./filament-error-fileupload-icon-1-1.md)
- [filament-error-fileupload-icon-1.md](./filament-error-fileupload-icon-1.md)
- [filament-error-fileupload-icon-2.md](./filament-error-fileupload-icon-2.md)
- [filament-error-fileupload-icon.md](./filament-error-fileupload-icon.md)
- [filament-error-fileupload-prefixicon-1-1.md](./filament-error-fileupload-prefixicon-1-1.md)
- [filament-error-fileupload-prefixicon-1.md](./filament-error-fileupload-prefixicon-1.md)
- [filament-error-fileupload-prefixicon-2.md](./filament-error-fileupload-prefixicon-2.md)
- [filament-error-fileupload-prefixicon.md](./filament-error-fileupload-prefixicon.md)
- [filament-extension-fixes.md](./filament-extension-fixes.md)
- [filament-fileupload-1-1.md](./filament-fileupload-1-1.md)
- [filament-fileupload-1.md](./filament-fileupload-1.md)
- [filament-fileupload-2.md](./filament-fileupload-2.md)
- [filament-fileupload-components-1-1.md](./filament-fileupload-components-1-1.md)
- [filament-fileupload-components-1.md](./filament-fileupload-components-1.md)
- [filament-fileupload-components-2.md](./filament-fileupload-components-2.md)
- [filament-fileupload-components.md](./filament-fileupload-components.md)
- [filament-fileupload.md](./filament-fileupload.md)
- [filament-groupcolumn-and-custom-columns.md](./filament-groupcolumn-and-custom-columns.md)
- [filament-pages-refactoring-1.md](./filament-pages-refactoring-1.md)
- [filament-pages-refactoring.md](./filament-pages-refactoring.md)
- [filament-pages-structure.md](./filament-pages-structure.md)
- [filament-resources-structure-1-1.md](./filament-resources-structure-1-1.md)
- [filament-resources-structure-1.md](./filament-resources-structure-1.md)
- [filament-resources-structure-2.md](./filament-resources-structure-2.md)
- [filament-resources-structure.md](./filament-resources-structure.md)
- [filament-v4-theme-upgrade.md](./filament-v4-theme-upgrade.md)
- [filament-version.md](./filament-version.md)
- [filament-vscode-1-1.md](./filament-vscode-1-1.md)
- [filament-vscode-1.md](./filament-vscode-1.md)
- [filament-vscode-2.md](./filament-vscode-2.md)
- [filament-vscode.md](./filament-vscode.md)
- [filament-widgets-frontend.md](./filament-widgets-frontend.md)
- [filament.md](./filament.md)
- [filamentropdown-avatar-components.md](./filamentropdown-avatar-components.md)
- [filamentropdown-avatar-usage.md](./filamentropdown-avatar-usage.md)
- [file-naming-rules.md](./file-naming-rules.md)
- [filosofia-modulo-ui.md](./filosofia-modulo-ui.md)
- [flags-components-1-1.md](./flags-components-1-1.md)
- [flags-components-1.md](./flags-components-1.md)
- [flags-components-2.md](./flags-components-2.md)
- [flags-components.md](./flags-components.md)
- [flip-cards.md](./flip-cards.md)
- [folio-volt-best-practices.md](./folio-volt-best-practices.md)
- [form-components-1-1.md](./form-components-1-1.md)
- [form-components-1.md](./form-components-1.md)
- [form-components-2.md](./form-components-2.md)
- [form-components.md](./form-components.md)
- [form-filament-widgets-1.md](./form-filament-widgets-1.md)
- [form-filament-widgets.md](./form-filament-widgets.md)
- [frontend.md](./frontend.md)
- [full-calendar.md](./full-calendar.md)
- [geo-boundary.md](./geo-boundary.md)
- [geo-dependency-violation-interactive-map.md](./geo-dependency-violation-interactive-map.md)
- [getting-started-1-1.md](./getting-started-1-1.md)
- [getting-started-1.md](./getting-started-1.md)
- [getting-started-2.md](./getting-started-2.md)
- [getting-started.md](./getting-started.md)
- [git-conflicts-inventory.md](./git-conflicts-inventory.md)
- [git-conflicts-resolution-summary.md](./git-conflicts-resolution-summary.md)
- [git-conflicts-resolution-sumy.md](./git-conflicts-resolution-sumy.md)
- [global-search.md](./global-search.md)
- [group-column-fix.md](./group-column-fix.md)
- [groupcolumn-relationship-resolution-analysis.md](./groupcolumn-relationship-resolution-analysis.md)
- [groupcolumn.md](./groupcolumn.md)
- [icon-state-column-business-logic.md](./icon-state-column-business-logic.md)
- [icon-system.md](./icon-system.md)
- [icons.md](./icons.md)
- [iconstatesplitcolumn-actions-implementation-1.md](./iconstatesplitcolumn-actions-implementation-1.md)
- [iconstatesplitcolumn-actions-implementation.md](./iconstatesplitcolumn-actions-implementation.md)
- [iconstatesplitcolumn-implementation-1-1.md](./iconstatesplitcolumn-implementation-1-1.md)
- [iconstatesplitcolumn-implementation-1.md](./iconstatesplitcolumn-implementation-1.md)
- [iconstatesplitcolumn-implementation-2.md](./iconstatesplitcolumn-implementation-2.md)
- [iconstatesplitcolumn-implementation.md](./iconstatesplitcolumn-implementation.md)
- [index.md](./index.md)
- [infolist-schema-guidelines-1.md](./infolist-schema-guidelines-1.md)
- [infolist-schema-guidelines.md](./infolist-schema-guidelines.md)
- [inline-date-picker-1-1.md](./inline-date-picker-1-1.md)
- [inline-date-picker-1.md](./inline-date-picker-1.md)
- [inline-date-picker-2.md](./inline-date-picker-2.md)
- [inline-date-picker.md](./inline-date-picker.md)
- [inlineate-picker.md](./inlineate-picker.md)
- [internal-debate-psr4-resolution.md](./internal-debate-psr4-resolution.md)
- [italian-language-corrections-1.md](./italian-language-corrections-1.md)
- [italian-language-corrections.md](./italian-language-corrections.md)
- [keting-components-implementation.md](./keting-components-implementation.md)
- [lang-link-1-1.md](./lang-link-1-1.md)
- [lang-link-1.md](./lang-link-1.md)
- [lang-link-2.md](./lang-link-2.md)
- [lang-link.md](./lang-link.md)
- [laravel-13-upgrade.md](./laravel-13-upgrade.md)
- [launch-plan.md](./launch-plan.md)
- [layout-components-1.md](./layout-components-1.md)
- [layout-components.md](./layout-components.md)
- [layouts-and-themes-1-1.md](./layouts-and-themes-1-1.md)
- [layouts-and-themes-1.md](./layouts-and-themes-1.md)
- [layouts-and-themes-2.md](./layouts-and-themes-2.md)
- [layouts-and-themes.md](./layouts-and-themes.md)
- [links.md](./links.md)
- [localization.md](./localization.md)
- [map-integration-guide.md](./map-integration-guide.md)
- [marketing-components-implementation.md](./marketing-components-implementation.md)
- [mcp-configuration.md](./mcp-configuration.md)
- [mcp-integration-1.md](./mcp-integration-1.md)
- [mcp-integration.md](./mcp-integration.md)
- [mcp-server-recommended-1.md](./mcp-server-recommended-1.md)
- [mcp-server-recommended.md](./mcp-server-recommended.md)
- [mcp-ui-ux.md](./mcp-ui-ux.md)
- [media.md](./media.md)
- [megamenu.md](./megamenu.md)
- [merge-conflict-files-list.md](./merge-conflict-files-list.md)
- [merge-conflicts-list.md](./merge-conflicts-list.md)
- [metodi-duplicati-analisi-1.md](./metodi-duplicati-analisi-1.md)
- [metodi-duplicati-analisi-2.md](./metodi-duplicati-analisi-2.md)
- [metodi-duplicati-analisi-3.md](./metodi-duplicati-analisi-3.md)
- [metodi-duplicati-analisi.md](./metodi-duplicati-analisi.md)
- [migrations.md](./migrations.md)
- [models-factory-seeder-analysis.md](./models-factory-seeder-analysis.md)
- [modularity-optimizations.md](./modularity-optimizations.md)
- [module-analysis-complete.md](./module-analysis-complete.md)
- [module-analysis.md](./module-analysis.md)
- [module-icons-design-system.md](./module-icons-design-system.md)
- [module-ui-1.md](./module-ui-1.md)
- [module-ui.md](./module-ui.md)
- [naming-conventions-1-1.md](./naming-conventions-1-1.md)
- [naming-conventions-1.md](./naming-conventions-1.md)
- [naming-conventions-2.md](./naming-conventions-2.md)
- [naming-conventions.md](./naming-conventions.md)
- [naming-rules-1-1.md](./naming-rules-1-1.md)
- [naming-rules-1.md](./naming-rules-1.md)
- [naming-rules-2.md](./naming-rules-2.md)
- [naming-rules.md](./naming-rules.md)
- [navbar.md](./navbar.md)
- [navigation-components-1-1.md](./navigation-components-1-1.md)
- [navigation-components-1.md](./navigation-components-1.md)
- [navigation-components-2.md](./navigation-components-2.md)
- [navigation-components.md](./navigation-components.md)
- [navigation.md](./navigation.md)
- [nestedset-migration-best-practices.md](./nestedset-migration-best-practices.md)
- [never-use-label-rule-1.md](./never-use-label-rule-1.md)
- [never-use-label-rule.md](./never-use-label-rule.md)
- [no-svg-hardcoded-in-blade.md](./no-svg-hardcoded-in-blade.md)
- [on-demand-pattern.md](./on-demand-pattern.md)
- [opening-hours-rule-localization-1.md](./opening-hours-rule-localization-1.md)
- [opening-hours-rule-localization.md](./opening-hours-rule-localization.md)
- [opening-hours-translation-fix-1.md](./opening-hours-translation-fix-1.md)
- [opening-hours-translation-fix.md](./opening-hours-translation-fix.md)
- [optimization-analysis-dry-kiss.md](./optimization-analysis-dry-kiss.md)
- [optimization-analysis.md](./optimization-analysis.md)
- [optimization-recommendations-1.md](./optimization-recommendations-1.md)
- [optimization-recommendations.md](./optimization-recommendations.md)
- [ottimizzazioni-approfondite-modulo-ui.md](./ottimizzazioni-approfondite-modulo-ui.md)
- [ottimizzazioni-modulo-ui.md](./ottimizzazioni-modulo-ui.md)
- [ottimizzazioni-super-dry-kiss.md](./ottimizzazioni-super-dry-kiss.md)
- [overview-extended.md](./overview-extended.md)
- [packages.md](./packages.md)
- [page-builder.md](./page-builder.md)
- [paths-and-assets-1-1.md](./paths-and-assets-1-1.md)
- [paths-and-assets-1.md](./paths-and-assets-1.md)
- [paths-and-assets-2.md](./paths-and-assets-2.md)
- [paths-and-assets.md](./paths-and-assets.md)
- [performance-optimization.md](./performance-optimization.md)
- [philosophy.md](./philosophy.md)
- [phpmd-improvements.md](./phpmd-improvements.md)
- [phpstan-compliance-status.md](./phpstan-compliance-status.md)
- [phpstan-compliance.md](./phpstan-compliance.md)
- [phpstan-corrections-.md](./phpstan-corrections-.md)
- [phpstan-corrections-archive-1.md](./phpstan-corrections-archive-1.md)
- [phpstan-corrections-final.md](./phpstan-corrections-final.md)
- [phpstan-corrections-gennaio-.md](./phpstan-corrections-gennaio-.md)
- [phpstan-corrections-gennaio-2025.md](./phpstan-corrections-gennaio-2025.md)
- [phpstan-corrections-gennaio-archive-1.md](./phpstan-corrections-gennaio-archive-1.md)
- [phpstan-corrections-gennaio.md](./phpstan-corrections-gennaio.md)
- [phpstan-corrections-january-.md](./phpstan-corrections-january-.md)
- [phpstan-corrections-january-archive-1.md](./phpstan-corrections-january-archive-1.md)
- [phpstan-corrections-january.md](./phpstan-corrections-january.md)
- [phpstan-corrections-renamed.md](./phpstan-corrections-renamed.md)
- [phpstan-corrections-summary-1.md](./phpstan-corrections-summary-1.md)
- [phpstan-corrections-summary.md](./phpstan-corrections-summary.md)
- [phpstan-corrections-sumy.md](./phpstan-corrections-sumy.md)
- [phpstan-corrections.md](./phpstan-corrections.md)
- [phpstan-error-analysis-strategy.md](./phpstan-error-analysis-strategy.md)
- [phpstan-error-analysis.md](./phpstan-error-analysis.md)
- [phpstan-errors-resolution.md](./phpstan-errors-resolution.md)
- [phpstan-errors-roadmap.md](./phpstan-errors-roadmap.md)
- [phpstan-fixes-.md](./phpstan-fixes-.md)
- [phpstan-fixes-1-1.md](./phpstan-fixes-1-1.md)
- [phpstan-fixes-1.md](./phpstan-fixes-1.md)
- [phpstan-fixes-2-1.md](./phpstan-fixes-2-1.md)
- [phpstan-fixes-2.md](./phpstan-fixes-2.md)
- [phpstan-fixes-2025-1.md](./phpstan-fixes-2025-1.md)
- [phpstan-fixes-2025.md](./phpstan-fixes-2025.md)
- [phpstan-fixes-3.md](./phpstan-fixes-3.md)
- [phpstan-fixes-archive-1.md](./phpstan-fixes-archive-1.md)
- [phpstan-fixes-archive-2.md](./phpstan-fixes-archive-2.md)
- [phpstan-fixes-archive-3.md](./phpstan-fixes-archive-3.md)
- [phpstan-fixes-archive-4.md](./phpstan-fixes-archive-4.md)
- [phpstan-fixes-archive-5.md](./phpstan-fixes-archive-5.md)
- [phpstan-fixes-conflict-d41d8c.md](./phpstan-fixes-conflict-d41d8c.md)
- [phpstan-fixes-conflict.md](./phpstan-fixes-conflict.md)
- [phpstan-fixes-gennaio-.md](./phpstan-fixes-gennaio-.md)
- [phpstan-fixes-gennaio-2025.md](./phpstan-fixes-gennaio-2025.md)
- [phpstan-fixes-gennaio-archive-1.md](./phpstan-fixes-gennaio-archive-1.md)
- [phpstan-fixes-gennaio.md](./phpstan-fixes-gennaio.md)
- [phpstan-fixes-january-.md](./phpstan-fixes-january-.md)
- [phpstan-fixes-january-1-1.md](./phpstan-fixes-january-1-1.md)
- [phpstan-fixes-january-1-archive-1.md](./phpstan-fixes-january-1-archive-1.md)
- [phpstan-fixes-january-1.md](./phpstan-fixes-january-1.md)
- [phpstan-fixes-january-2025.md](./phpstan-fixes-january-2025.md)
- [phpstan-fixes-january-archive-1.md](./phpstan-fixes-january-archive-1.md)
- [phpstan-fixes-january.md](./phpstan-fixes-january.md)
- [phpstan-fixes-november-.md](./phpstan-fixes-november-.md)
- [phpstan-fixes-november-2025.md](./phpstan-fixes-november-2025.md)
- [phpstan-fixes-november-archive-1.md](./phpstan-fixes-november-archive-1.md)
- [phpstan-fixes-november.md](./phpstan-fixes-november.md)
- [phpstan-fixes-summary.md](./phpstan-fixes-summary.md)
- [phpstan-fixes-sumy.md](./phpstan-fixes-sumy.md)
- [phpstan-fixes.md](./phpstan-fixes.md)
- [phpstan-level-10-cleanup.md](./phpstan-level-10-cleanup.md)
- [phpstan-level-10-compliance.md](./phpstan-level-10-compliance.md)
- [phpstan-level10-bugfixes-comprehensive.md](./phpstan-level10-bugfixes-comprehensive.md)
- [phpstan-namespace-correction-1.md](./phpstan-namespace-correction-1.md)
- [phpstan-namespace-correction.md](./phpstan-namespace-correction.md)
- [phpstan-patterns.md](./phpstan-patterns.md)
- [phpstan-property-exists-elimination.md](./phpstan-property-exists-elimination.md)
- [phpstan-radio-badge-fix-1-1.md](./phpstan-radio-badge-fix-1-1.md)
- [phpstan-radio-badge-fix-1.md](./phpstan-radio-badge-fix-1.md)
- [phpstan-radio-badge-fix-2.md](./phpstan-radio-badge-fix-2.md)
- [phpstan-radio-badge-fix.md](./phpstan-radio-badge-fix.md)
- [phpstan-roadmap.md](./phpstan-roadmap.md)
- [phpstan-status.md](./phpstan-status.md)
- [phpstan.md](./phpstan.md)
- [ponytail-audit-over-engineering.md](./ponytail-audit-over-engineering.md)
- [prd.md](./prd.md)
- [product-launch-plan-1.md](./product-launch-plan-1.md)
- [product-launch-plan.md](./product-launch-plan.md)
- [product-requirements.md](./product-requirements.md)
- [product-roadmap-1.md](./product-roadmap-1.md)
- [product-roadmap.md](./product-roadmap.md)
- [product-strategy-1.md](./product-strategy-1.md)
- [product-strategy.md](./product-strategy.md)
- [project-structure.md](./project-structure.md)
- [prompt-rules-link-1.md](./prompt-rules-link-1.md)
- [prompt-rules-link.md](./prompt-rules-link.md)
- [psr4-autoloading-error-analysis.md](./psr4-autoloading-error-analysis.md)
- [psr4-fix-implementation-plan.md](./psr4-fix-implementation-plan.md)
- [psr4-namespace-violations.md](./psr4-namespace-violations.md)
- [public-resources-management-1-1.md](./public-resources-management-1-1.md)
- [public-resources-management-1.md](./public-resources-management-1.md)
- [public-resources-management-2.md](./public-resources-management-2.md)
- [public-resources-management.md](./public-resources-management.md)
- [qmd-setup.md](./qmd-setup.md)
- [qrcode.md](./qrcode.md)
- [quality-report.md](./quality-report.md)
- [radio-collection-component-1.md](./radio-collection-component-1.md)
- [radio-collection-component.md](./radio-collection-component.md)
- [ratings.md](./ratings.md)
- [readme-en.md](./readme-en.md)
- [readme.md](./README.md)
- [redundancy-analysis.md](./redundancy-analysis.md)
- [redundancy-audit-1.md](./redundancy-audit-1.md)
- [redundancy-audit-2026-05-21.deprecated.md](./redundancy-audit-2026-05-21.deprecated.md)
- [redundancy-audit.md](./redundancy-audit.md)
- [redundancy-report.md](./redundancy-report.md)
- [release-marketing-standard.md](./release-marketing-standard.md)
- [resolution-conflitti-tablelayouttrait.md](./resolution-conflitti-tablelayouttrait.md)
- [risoluzione-conflitti-tablelayouttrait-1.md](./risoluzione-conflitti-tablelayouttrait-1.md)
- [risoluzione-conflitti-tablelayouttrait.md](./risoluzione-conflitti-tablelayouttrait.md)
- [roadmap-.md](./roadmap-.md)
- [roadmap-2025.md](./roadmap-2025.md)
- [roadmap-and-issues.md](./roadmap-and-issues.md)
- [roadmap-archive-1.md](./roadmap-archive-1.md)
- [roadmap-conflict.md](./roadmap-conflict.md)
- [roadmap.md](./roadmap.md)
- [root-file-policy.md](./root-file-policy.md)
- [root-files-hygiene.md](./root-files-hygiene.md)
- [rules-index.md](./rules-index.md)
- [rules-testing-no-migrate-fresh.md](./rules-testing-no-migrate-fresh.md)
- [s3test-bugfix-null-errorcode.md](./s3test-bugfix-null-errorcode.md)
- [s3test-critical-errors-analysis.md](./s3test-critical-errors-analysis.md)
- [s3test-method-duplication-bugfix.md](./s3test-method-duplication-bugfix.md)
- [s3test.md](./s3test.md)
- [schema.md](./schema.md)
- [second-brain.md](./second-brain.md)
- [selectstatecolumn-confirmation-modal-1-1.md](./selectstatecolumn-confirmation-modal-1-1.md)
- [selectstatecolumn-confirmation-modal-1.md](./selectstatecolumn-confirmation-modal-1.md)
- [selectstatecolumn-confirmation-modal-2.md](./selectstatecolumn-confirmation-modal-2.md)
- [selectstatecolumn-confirmation-modal.md](./selectstatecolumn-confirmation-modal.md)
- [selectstatecolumn.md](./selectstatecolumn.md)
- [spatie-media-library-migration-1-1.md](./spatie-media-library-migration-1-1.md)
- [spatie-media-library-migration-1.md](./spatie-media-library-migration-1.md)
- [spatie-media-library-migration-2.md](./spatie-media-library-migration-2.md)
- [spatie-media-library-migration.md](./spatie-media-library-migration.md)
- [sprint-planning-1.md](./sprint-planning-1.md)
- [sprint-planning-meeting.md](./sprint-planning-meeting.md)
- [sprint-planning.md](./sprint-planning.md)
- [state-transitions-1.md](./state-transitions-1.md)
- [state-transitions.md](./state-transitions.md)
- [strategy.md](./strategy.md)
- [strict-types-implementation-1.md](./strict-types-implementation-1.md)
- [strict-types-implementation.md](./strict-types-implementation.md)
- [structure.md](./structure.md)
- [struttura-themes-folio-1.md](./struttura-themes-folio-1.md)
- [struttura-themes-folio.md](./struttura-themes-folio.md)
- [studio-card-selector-implementation-1-1.md](./studio-card-selector-implementation-1-1.md)
- [studio-card-selector-implementation-1.md](./studio-card-selector-implementation-1.md)
- [studio-card-selector-implementation-2.md](./studio-card-selector-implementation-2.md)
- [studio-card-selector-implementation.md](./studio-card-selector-implementation.md)
- [svg-icons-automatic-registration.md](./svg-icons-automatic-registration.md)
- [svg-icons-complete.md](./svg-icons-complete.md)
- [table-components-1-1.md](./table-components-1-1.md)
- [table-components-1.md](./table-components-1.md)
- [table-components-2.md](./table-components-2.md)
- [table-components.md](./table-components.md)
- [table-layout-enum-analysis-1.md](./table-layout-enum-analysis-1.md)
- [table-layout-enum-analysis.md](./table-layout-enum-analysis.md)
- [table-layout-enum-complete-guide.md](./table-layout-enum-complete-guide.md)
- [table-layout-enum-comprehensive.md](./table-layout-enum-comprehensive.md)
- [table-layout-enum-implementation-example-1.md](./table-layout-enum-implementation-example-1.md)
- [table-layout-enum-implementation-example.md](./table-layout-enum-implementation-example.md)
- [table-layout-enum-usage-1-1.md](./table-layout-enum-usage-1-1.md)
- [table-layout-enum-usage-1.md](./table-layout-enum-usage-1.md)
- [table-layout-enum-usage-2.md](./table-layout-enum-usage-2.md)
- [table-layout-enum-usage.md](./table-layout-enum-usage.md)
- [tailwind-themes.md](./tailwind-themes.md)
- [task-consolidare-documentazione.md](./task-consolidare-documentazione.md)
- [task-location-selector.md](./task-location-selector.md)
- [task-ridurre-phpstan-suppressioni.md](./task-ridurre-phpstan-suppressioni.md)
- [task-test-livewire-components.md](./task-test-livewire-components.md)
- [test-conflicts-resolution-1.md](./test-conflicts-resolution-1.md)
- [test-conflicts-resolution.md](./test-conflicts-resolution.md)
- [test-fix-philosophy.md](./test-fix-philosophy.md)
- [test-structure-cleanup.md](./test-structure-cleanup.md)
- [test.md](./test.md)
- [testing-rules.md](./testing-rules.md)
- [testing.md](./testing.md)
- [theme-build.md](./theme-build.md)
- [theme-translation-sync.md](./theme-translation-sync.md)
- [theme-widget-translations.md](./theme-widget-translations.md)
- [theme.md](./theme.md)
- [transclass-rule-1.md](./transclass-rule-1.md)
- [transclass-rule.md](./transclass-rule.md)
- [translations-update-archive-1.md](./translations-update-archive-1.md)
- [translations-update-january-2026.md](./translations-update-january-2026.md)
- [translations-update-january.md](./translations-update-january.md)
- [translations-update-renamed.md](./translations-update-renamed.md)
- [translations-update.md](./translations-update.md)
- [translations.md](./translations.md)
- [troubleshooting.md](./troubleshooting.md)
- [ubuntu.md](./ubuntu.md)
- [ui-module.md](./ui-module.md)
- [ui-table-layout-enum.md](./ui-table-layout-enum.md)
- [ui.md](./ui.md)
- [user-research-1.md](./user-research-1.md)
- [user-research.md](./user-research.md)
- [validation-files-multilingua-1.md](./validation-files-multilingua-1.md)
- [validation-files-multilingua.md](./validation-files-multilingua.md)
- [vscode-filament-extension-1.md](./vscode-filament-extension-1.md)
- [vscode-filament-extension.md](./vscode-filament-extension.md)
- [vscode-filament-plugin-1-1.md](./vscode-filament-plugin-1-1.md)
- [vscode-filament-plugin-1.md](./vscode-filament-plugin-1.md)
- [vscode-filament-plugin-2.md](./vscode-filament-plugin-2.md)
- [vscode-filament-plugin.md](./vscode-filament-plugin.md)
- [vscode-php-setup-1.md](./vscode-php-setup-1.md)
- [vscode-php-setup.md](./vscode-php-setup.md)
- [widget-optimization-1.md](./widget-optimization-1.md)
- [widget-optimization.md](./widget-optimization.md)
- [widgets.md](./widgets.md)

## root-md-files

- [api-relocated.md](./root-md-files/api-relocated.md)
- [api.md](./root-md-files/api.md)
- [blocks-relocated.md](./root-md-files/blocks-relocated.md)
- [blocks.md](./root-md-files/blocks.md)
- [carousel-slider.md](./root-md-files/carousel-slider.md)
- [changelog.md](./root-md-files/CHANGELOG.md)
- [chunk.md](./root-md-files/chunk.md)
- [ci.md](./root-md-files/ci.md)
- [custom-firm-fields.md](./root-md-files/custom-firm-fields.md)
- [custom-theme.md](./root-md-files/custom-theme.md)
- [eav.md](./root-md-files/eav.md)
- [effetcts.md](./root-md-files/effetcts.md)
- [filament.md](./root-md-files/filament.md)
- [flip-cards.md](./root-md-files/flip-cards.md)
- [global-search.md](./root-md-files/global-search.md)
- [links.md](./root-md-files/links.md)
- [media.md](./root-md-files/media.md)
- [megamenu.md](./root-md-files/megamenu.md)
- [navbar.md](./root-md-files/navbar.md)
- [page-builder.md](./root-md-files/page-builder.md)
- [qrcode.md](./root-md-files/qrcode.md)
- [ratings.md](./root-md-files/ratings.md)
- [tailwind-themes.md](./root-md-files/tailwind-themes.md)
- [test.md](./root-md-files/test.md)
- [theme.md](./root-md-files/theme.md)
- [ubuntu.md](./root-md-files/ubuntu.md)
- [widgets.md](./root-md-files/widgets.md)

## standards

- [accessibility.md](./standards/accessibility.md)
- [auth-form-standards-1.md](./standards/auth-form-standards-1.md)
- [auth-form-standards.md](./standards/auth-form-standards.md)
- [form-standards-1.md](./standards/form-standards-1.md)
- [form-standards.md](./standards/form-standards.md)
- [performance.md](./standards/performance.md)
- [ui-standards.md](./standards/ui-standards.md)

## tasks

- [001-design-system-components.md](./tasks/001-design-system-components.md)
- [cleanup-redundant-files.md](./tasks/cleanup-redundant-files.md)
- [filament-v5-alignment.md](./tasks/filament-v5-alignment.md)
- [increase-test-coverage.md](./tasks/increase-test-coverage.md)
- [refactor-complex-components.md](./tasks/refactor-complex-components.md)
- [tasks-index.md](./tasks/tasks-index.md)
- [ui-cleanup-docs.md](./tasks/ui-cleanup-docs.md)
- [ui-filament-v5.md](./tasks/ui-filament-v5.md)

## testing

- [pest-testing-guide.md](./testing/pest-testing-guide.md)

## themes

- [asset-management-1.md](./themes/asset-management-1.md)
- [asset-management.md](./themes/asset-management.md)
- [compilation.md](./themes/compilation.md)
- [components.md](./themes/components.md)
- [optimizations.md](./themes/optimizations.md)
- [schemaless-attributes-guide.md](./themes/schemaless-attributes-guide.md)

## translations

- [lang-service-provider.md](./translations/lang-service-provider.md)

## wiki

- [agents.md](./wiki/AGENTS.md)
- [bmad-method.md](./wiki/bmad-method.md)
- [context-compression.md](./wiki/context-compression.md)
- [index.md](./wiki/index.md)
- [log.md](./wiki/log.md)
- [overview.md](./wiki/overview.md)

## wiki/concepts

- [auth-register-focus-loss-overlay.md](./wiki/concepts/auth-register-focus-loss-overlay.md)
- [block-rendering-and-optional-services.md](./wiki/concepts/block-rendering-and-optional-services.md)
- [claude-audit-static.md](./wiki/concepts/claude-audit-static.md)
- [code-redundancy-ui.md](./wiki/concepts/code-redundancy-ui.md)
- [context-overflow-prevention.md](./wiki/concepts/context-overflow-prevention.md)
- [enum-select-best-practices.md](./wiki/concepts/enum-select-best-practices.md)
- [enum-select-component.md](./wiki/concepts/enum-select-component.md)
- [enum-select-contract-and-false-friends.md](./wiki/concepts/enum-select-contract-and-false-friends.md)
- [enum-select-usage.md](./wiki/concepts/enum-select-usage.md)
- [enumselect-filament-api-collisions.md](./wiki/concepts/enumselect-filament-api-collisions.md)
- [filament-first-blade-canonical.md](./wiki/concepts/filament-first-blade-canonical.md)
- [method-name-homonyms.md](./wiki/concepts/method-name-homonyms.md)
- [model-states-module-ownership.md](./wiki/concepts/model-states-module-ownership.md)
- [module-filament-component-autoload-rule.md](./wiki/concepts/module-filament-component-autoload-rule.md)
- [module-root-uppercase-folders-archive.md](./wiki/concepts/module-root-uppercase-folders-archive.md)
- [no-app-support-queueable-actions.md](./wiki/concepts/no-app-support-queueable-actions.md)
- [no-services-no-support-queueable-actions.md](./wiki/concepts/no-services-no-support-queueable-actions.md)
- [organizzativa-money.md](./wiki/concepts/organizzativa-money.md)
- [phpstan-compliance.md](./wiki/concepts/phpstan-compliance.md)
- [phpstan-dynamic-array-normalization.md](./wiki/concepts/phpstan-dynamic-array-normalization.md)
- [ponytail-audit.md](./wiki/concepts/ponytail-audit.md)
- [second-brain-local-discipline.md](./wiki/concepts/second-brain-local-discipline.md)
- [testing.md](./wiki/concepts/testing.md)
- [ui-operating-model.md](./wiki/concepts/ui-operating-model.md)
- [ui-services-support-to-actions.md](./wiki/concepts/ui-services-support-to-actions.md)
- [xotbasefield-no-view-rule.md](./wiki/concepts/xotbasefield-no-view-rule.md)

## wiki/memories

- [lang-split-ui-claude-audit.md](./wiki/memories/lang-split-ui-claude-audit.md)

## wiki/overviews

- [ui-module.md](./wiki/overviews/ui-module.md)

## wiki/sources

- [ui-architecture-sources.md](./wiki/sources/ui-architecture-sources.md)

## wiki/troubleshooting

- [git-merge-conflict-inventory-1.md](./wiki/troubleshooting/git-merge-conflict-inventory-1.md)
- [git-merge-conflict-inventory-2026-04-28.deprecated.md](./wiki/troubleshooting/git-merge-conflict-inventory-2026-04-28.deprecated.md)
- [git-merge-conflict-inventory.md](./wiki/troubleshooting/git-merge-conflict-inventory.md)
- [module-theme-root-hygiene.md](./wiki/troubleshooting/module-theme-root-hygiene.md)
- [phpstan-fixes-1.md](./wiki/troubleshooting/phpstan-fixes-1.md)
- [phpstan-fixes-2026-05-06.deprecated.md](./wiki/troubleshooting/phpstan-fixes-2026-05-06.deprecated.md)
- [phpstan-fixes.md](./wiki/troubleshooting/phpstan-fixes.md)
- [git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md)
- [phpstan-fixes-1.md](./wiki/troubleshooting/phpstan-fixes-1.md)
- [phpstan-fixes-2026-05-06.deprecated.md](./wiki/troubleshooting/phpstan-fixes-2026-05-06.deprecated.md)
- [phpstan-fixes.md](./wiki/troubleshooting/phpstan-fixes.md)
---
## Contenuto assorbito da `INDEX.md`
- [component-registration.md](./architecture/component-registration.md)
- [filament-pages-structure.md](./architecture/filament-pages-structure.md)
- [filament-resources-structure.md](./architecture/filament-resources-structure.md)
- [structure.md](./architecture/structure.md)
## archive
- [advanced-form-components.md](./archive/advanced-form-components.md)
- [algolia-docsearch-1.md](./archive/algolia-docsearch-1.md)
- [algolia-docsearch.md](./archive/algolia-docsearch.md)
- [architecture-rules-1.md](./archive/architecture-rules-1.md)
- [architecture-rules-2.md](./archive/architecture-rules-2.md)
- [architecture-rules.md](./archive/architecture-rules.md)
- [auth-pages.md](./archive/auth-pages.md)
- [base-components.md](./archive/base-components.md)
- [best-practices-1.md](./archive/best-practices-1.md)
- [best-practices.md](./archive/best-practices.md)
- [blade-data-handling-1.md](./archive/blade-data-handling-1.md)
- [blade-data-handling.md](./archive/blade-data-handling.md)
- [blocks-system-1.md](./archive/blocks-system-1.md)
- [blocks-system.md](./archive/blocks-system.md)
- [bugfix-icons-missing-1.md](./archive/bugfix-icons-missing-1.md)
- [bugfix-icons-missing-2025-01-27.deprecated.md](./archive/bugfix-icons-missing-2025-01-27.deprecated.md)
- [bugfix-icons-missing.md](./archive/bugfix-icons-missing.md)
- [bugfix-table-layout-action-1.md](./archive/bugfix-table-layout-action-1.md)
- [bugfix-table-layout-action-2025-01-27.deprecated.md](./archive/bugfix-table-layout-action-2025-01-27.deprecated.md)
- [bugfix-table-layout-action.md](./archive/bugfix-table-layout-action.md)
- [chart-components-1.md](./archive/chart-components-1.md)
- [chart-components.md](./archive/chart-components.md)
- [cms-link-1.md](./archive/cms-link-1.md)
- [cms-link.md](./archive/cms-link.md)
- [cms-themes-link-1.md](./archive/cms-themes-link-1.md)
- [cms-themes-link.md](./archive/cms-themes-link.md)
- [components-guide-1.md](./archive/components-guide-1.md)
- [components-guide.md](./archive/components-guide.md)
- [conflict-resolution-iconstatecolumn.md](./archive/conflict-resolution-iconstatecolumn.md)
- [conflict-resolution-locationselector.md](./archive/conflict-resolution-locationselector.md)
- [conflict-resolution-tablelayoutenum.md](./archive/conflict-resolution-tablelayoutenum.md)
- [conflict-resolution-translation-files.md](./archive/conflict-resolution-translation-files.md)
- [convenzioni-naming-campi-1.md](./archive/convenzioni-naming-campi-1.md)
- [convenzioni-naming-campi.md](./archive/convenzioni-naming-campi.md)
- [custom-404-page-1.md](./archive/custom-404-page-1.md)
- [custom-404-page.md](./archive/custom-404-page.md)
- [customizing-your-site-1.md](./archive/customizing-your-site-1.md)
- [customizing-your-site.md](./archive/customizing-your-site.md)
- [data-display-components.md](./archive/data-display-components.md)
- [design-system-1.md](./archive/design-system-1.md)
- [design-system.md](./archive/design-system.md)
- [feedback-components.md](./archive/feedback-components.md)
- [filament-4x-upgrade-report.md](./archive/filament-4x-upgrade-report.md)
- [filament-4x-upgrade.md](./archive/filament-4x-upgrade.md)
- [filament-blade-components-usage-1.md](./archive/filament-blade-components-usage-1.md)
- [filament-blade-components-usage-2.md](./archive/filament-blade-components-usage-2.md)
- [filament-blade-components-usage.md](./archive/filament-blade-components-usage.md)
- [filament-components-1.md](./archive/filament-components-1.md)
- [filament-components-errors-1.md](./archive/filament-components-errors-1.md)
- [filament-components-errors.md](./archive/filament-components-errors.md)
- [filament-components-location-studio-1.md](./archive/filament-components-location-studio-1.md)
- [filament-components-location-studio.md](./archive/filament-components-location-studio.md)
- [filament-components-usage-1.md](./archive/filament-components-usage-1.md)
- [filament-components-usage-2.md](./archive/filament-components-usage-2.md)
- [filament-components-usage.md](./archive/filament-components-usage.md)
- [filament-components.md](./archive/filament-components.md)
- [filament-dropdown-avatar-components.md](./archive/filament-dropdown-avatar-components.md)
- [filament-dropdown-avatar-usage-1.md](./archive/filament-dropdown-avatar-usage-1.md)
- [filament-dropdown-avatar-usage.md](./archive/filament-dropdown-avatar-usage.md)
- [filament-error-fileupload-buttonlabel-1.md](./archive/filament-error-fileupload-buttonlabel-1.md)
- [filament-error-fileupload-buttonlabel.md](./archive/filament-error-fileupload-buttonlabel.md)
- [filament-error-fileupload-icon-1.md](./archive/filament-error-fileupload-icon-1.md)
- [filament-error-fileupload-icon.md](./archive/filament-error-fileupload-icon.md)
- [filament-error-fileupload-prefixicon-1.md](./archive/filament-error-fileupload-prefixicon-1.md)
- [filament-error-fileupload-prefixicon.md](./archive/filament-error-fileupload-prefixicon.md)
- [filament-fileupload-1.md](./archive/filament-fileupload-1.md)
- [filament-fileupload-components-1.md](./archive/filament-fileupload-components-1.md)
- [filament-fileupload-components.md](./archive/filament-fileupload-components.md)
- [filament-fileupload.md](./archive/filament-fileupload.md)
- [filament-pages-refactoring.md](./archive/filament-pages-refactoring.md)
- [filament-resources-structure-1.md](./archive/filament-resources-structure-1.md)
- [filament-resources-structure.md](./archive/filament-resources-structure.md)
- [filament-v4-theme-upgrade.md](./archive/filament-v4-theme-upgrade.md)
- [filament-vscode-1.md](./archive/filament-vscode-1.md)
- [filament-vscode.md](./archive/filament-vscode.md)
- [flags-components-1.md](./archive/flags-components-1.md)
- [flags-components-2.md](./archive/flags-components-2.md)
- [flags-components.md](./archive/flags-components.md)
- [form-components-1.md](./archive/form-components-1.md)
- [form-components.md](./archive/form-components.md)
- [getting-started-1.md](./archive/getting-started-1.md)
- [getting-started.md](./archive/getting-started.md)
- [iconstatesplitcolumn-actions-implementation.md](./archive/iconstatesplitcolumn-actions-implementation.md)
- [iconstatesplitcolumn-implementation-1.md](./archive/iconstatesplitcolumn-implementation-1.md)
- [iconstatesplitcolumn-implementation.md](./archive/iconstatesplitcolumn-implementation.md)
- [inline-date-picker-1.md](./archive/inline-date-picker-1.md)
- [inline-date-picker.md](./archive/inline-date-picker.md)
- [italian-language-corrections.md](./archive/italian-language-corrections.md)
- [lang-link-1.md](./archive/lang-link-1.md)
- [lang-link.md](./archive/lang-link.md)
- [layout-components.md](./archive/layout-components.md)
- [layouts-and-themes-1.md](./archive/layouts-and-themes-1.md)
- [layouts-and-themes.md](./archive/layouts-and-themes.md)
- [mcp-integration-1.md](./archive/mcp-integration-1.md)
- [mcp-integration.md](./archive/mcp-integration.md)
- [mcp-server-recommended.md](./archive/mcp-server-recommended.md)
- [naming-conventions-1.md](./archive/naming-conventions-1.md)
- [naming-conventions.md](./archive/naming-conventions.md)
- [naming-rules-1.md](./archive/naming-rules-1.md)
- [naming-rules.md](./archive/naming-rules.md)
- [navigation-components-1.md](./archive/navigation-components-1.md)
- [navigation-components-2.md](./archive/navigation-components-2.md)
- [navigation-components.md](./archive/navigation-components.md)
- [never-use-label-rule.md](./archive/never-use-label-rule.md)
- [opening-hours-rule-localization.md](./archive/opening-hours-rule-localization.md)
- [opening-hours-translation-fix.md](./archive/opening-hours-translation-fix.md)
- [optimization-recommendations.md](./archive/optimization-recommendations.md)
- [paths-and-assets-1.md](./archive/paths-and-assets-1.md)
- [paths-and-assets-2.md](./archive/paths-and-assets-2.md)
- [paths-and-assets.md](./archive/paths-and-assets.md)
- [phpstan-corrections-summary.md](./archive/phpstan-corrections-summary.md)
- [phpstan-fixes-1.md](./archive/phpstan-fixes-1.md)
- [phpstan-fixes-2025.md](./archive/phpstan-fixes-2025.md)
- [phpstan-fixes.md](./archive/phpstan-fixes.md)
- [phpstan-level-10-cleanup.md](./archive/phpstan-level-10-cleanup.md)
- [phpstan-level-10-compliance.md](./archive/phpstan-level-10-compliance.md)
- [phpstan-radio-badge-fix-1.md](./archive/phpstan-radio-badge-fix-1.md)
- [phpstan-radio-badge-fix.md](./archive/phpstan-radio-badge-fix.md)
- [public-resources-management-1.md](./archive/public-resources-management-1.md)
- [public-resources-management-2.md](./archive/public-resources-management-2.md)
- [public-resources-management.md](./archive/public-resources-management.md)
- [radio-collection-component.md](./archive/radio-collection-component.md)
- [selectstatecolumn-confirmation-modal-1.md](./archive/selectstatecolumn-confirmation-modal-1.md)
- [selectstatecolumn-confirmation-modal.md](./archive/selectstatecolumn-confirmation-modal.md)
- [spatie-media-library-migration-1.md](./archive/spatie-media-library-migration-1.md)
- [spatie-media-library-migration.md](./archive/spatie-media-library-migration.md)
- [state-transitions.md](./archive/state-transitions.md)
- [strict-types-implementation.md](./archive/strict-types-implementation.md)
- [struttura-themes-folio-1.md](./archive/struttura-themes-folio-1.md)
- [struttura-themes-folio.md](./archive/struttura-themes-folio.md)
- [studio-card-selector-implementation-1.md](./archive/studio-card-selector-implementation-1.md)
- [studio-card-selector-implementation.md](./archive/studio-card-selector-implementation.md)
- [table-components-1.md](./archive/table-components-1.md)
- [table-components.md](./archive/table-components.md)
- [table-layout-enum-analysis.md](./archive/table-layout-enum-analysis.md)
- [table-layout-enum-implementation-example.md](./archive/table-layout-enum-implementation-example.md)
- [table-layout-enum-usage-1.md](./archive/table-layout-enum-usage-1.md)
- [table-layout-enum-usage.md](./archive/table-layout-enum-usage.md)
- [transclass-rule.md](./archive/transclass-rule.md)
- [validation-files-multilingua.md](./archive/validation-files-multilingua.md)
- [vscode-filament-extension.md](./archive/vscode-filament-extension.md)
- [vscode-filament-plugin-1.md](./archive/vscode-filament-plugin-1.md)
- [vscode-filament-plugin.md](./archive/vscode-filament-plugin.md)
- [vscode-php-setup.md](./archive/vscode-php-setup.md)
- [widget-optimization.md](./archive/widget-optimization.md)
## best-practices
- [naming-conventions.md](./best-practices/naming-conventions.md)
## blade
- [component-registration.md](./blade/component-registration.md)
- [filament-components.md](./blade/filament-components.md)
## blocks
- [correct-filament-components.md](./blocks/correct-filament-components.md)
- [filament-component-integration.md](./blocks/filament-component-integration.md)
- [logo.md](./blocks/logo.md)
- [navigation.md](./blocks/navigation.md)
- [user-dropdown.md](./blocks/user-dropdown.md)
## bugfix
- [groupcolumn-architectural-violations.md](./bugfix/groupcolumn-architectural-violations.md)
- [iconcolumn-extends-filament-column.md](./bugfix/iconcolumn-extends-filament-column.md)
- [iconcolumn-view-path-fix.md](./bugfix/iconcolumn-view-path-fix.md)
## charts
- [chartjs-datalabels-multiple-labels-complete-guide.md](./charts/chartjs-datalabels-multiple-labels-complete-guide.md)
- [chartjs-plugin-datalabels-filament5.md](./charts/chartjs-plugin-datalabels-filament5.md)
- [export-strategy.md](./charts/export-strategy.md)
- [filament-chart-js-guide.md](./charts/filament-chart-js-guide.md)
- [server-side-actions.md](./charts/server-side-actions.md)
- [shared-hosting-strategy.md](./charts/shared-hosting-strategy.md)
## clean-code
- [no-obvious-comments.md](./clean-code/no-obvious-comments.md)
- [syntax-error-fixes.md](./clean-code/syntax-error-fixes.md)
- [wizard-schema-aration.md](./clean-code/wizard-schema-aration.md)
- [wizard-schema-separation.md](./clean-code/wizard-schema-separation.md)
- [wizard-steps.md](./clean-code/wizard-steps.md)
## components
- [address-field-1.md](./components/address-field-1.md)
- [address-field.md](./components/address-field.md)
- [blade-component-registration.md](./components/blade-component-registration.md)
- [filament-usage.md](./components/filament-usage.md)
- [filament.md](./components/filament.md)
- [file-upload.md](./components/file-upload.md)
- [footer.md](./components/footer.md)
- [full-calendar-1.md](./components/full-calendar-1.md)
- [full-calendar.md](./components/full-calendar.md)
- [iconstatesplicolumn-improvements.md](./components/iconstatesplicolumn-improvements.md)
- [inline-date-picker-component.md](./components/inline-date-picker-component.md)
- [inline-date-picker.md](./components/inline-date-picker.md)
- [opening-hours-field.md](./components/opening-hours-field.md)
- [page-component-migration.md](./components/page-component-migration.md)
- [radio-card-selector-component.md](./components/radio-card-selector-component.md)
- [radio-collection-component.md](./components/radio-collection-component.md)
- [radio-collection-debugging.md](./components/radio-collection-debugging.md)
- [radio-collection-fix-summary.md](./components/radio-collection-fix-summary.md)
- [radio-collection-fix-sumy.md](./components/radio-collection-fix-sumy.md)
- [radio-collection-implementation.md](./components/radio-collection-implementation.md)
- [radio-collection-philosophy.md](./components/radio-collection-philosophy.md)
- [radio-collection-usage-examples.md](./components/radio-collection-usage-examples.md)
- [studio-card-selector-component.md](./components/studio-card-selector-component.md)
- [studio-selection-component.md](./components/studio-selection-component.md)
- [table-columns.md](./components/table-columns.md)
- [ui-components.md](./components/ui-components.md)
- [volt.md](./components/volt.md)
## components/archive
- [full-calendar-1.md](./components/archive/full-calendar-1.md)
- [full-calendar.md](./components/archive/full-calendar.md)
## components/legacy
- [full-calendar-1.md](./components/legacy/full-calendar-1.md)
- [full-calendar.md](./components/legacy/full-calendar.md)
## components/ui_components
- [full-calendar.md](./components/ui_components/full-calendar.md)
## core
- [architecture.md](./core/architecture.md)
## development
- [roadmap.md](./development/roadmap.md)
## development/roadmap
- [bottlenecks.md](./development/roadmap/bottlenecks.md)
- [component-system.md](./development/roadmap/component-system.md)
- [form-component.md](./development/roadmap/form-component.md)
- [form-components.md](./development/roadmap/form-components.md)
- [theme-system.md](./development/roadmap/theme-system.md)
## examples
- [inline-date-picker-usage.md](./examples/inline-date-picker-usage.md)
- [table-layout-implementation-example.md](./examples/table-layout-implementation-example.md)
## filament
- [automatic-translations.md](./filament/automatic-translations.md)
- [best-practices.md](./filament/best-practices.md)
- [component-icon-support.md](./filament/component-icon-support.md)
- [component-methods-compatibility.md](./filament/component-methods-compatibility.md)
- [filament-4-components-guide.md](./filament/filament-4-components-guide.md)
- [filament-4-migration-guide.md](./filament/filament-4-migration-guide.md)
- [filament-4-migration-summary.md](./filament/filament-4-migration-summary.md)
- [filament-4-migration-sumy.md](./filament/filament-4-migration-sumy.md)
- [file-upload-component.md](./filament/file-upload-component.md)
- [installation.md](./filament/installation.md)
- [label-translation-system.md](./filament/label-translation-system.md)
- [list-records.md](./filament/list-records.md)
- [listrecords-1.md](./filament/listrecords-1.md)
- [listrecords.md](./filament/listrecords.md)
- [modules.md](./filament/modules.md)
- [nested-resource.md](./filament/nested-resource.md)
- [no-label-rule.md](./filament/no-label-rule.md)
- [pulse.md](./filament/pulse.md)
- [resource.md](./filament/resource.md)
- [resources.md](./filament/resources.md)
- [theme.md](./filament/theme.md)
- [vendor.md](./filament/vendor.md)
- [wizard-best-practices.md](./filament/wizard-best-practices.md)
- [wizard-step-naming.md](./filament/wizard-step-naming.md)
## filament-components
- [file-upload.md](./filament-components/file-upload.md)
## filament/actions
- [attach.md](./filament/actions/attach.md)
- [pdf.md](./filament/actions/pdf.md)
## filament/archive
- [listrecords-1.md](./filament/archive/listrecords-1.md)
- [listrecords.md](./filament/archive/listrecords.md)
## filament/errors
- [common-errors.md](./filament/errors/common-errors.md)
- [dropdown-list-item-tag.md](./filament/errors/dropdown-list-item-tag.md)
- [static-instance-method-incompatibility.md](./filament/errors/static-instance-method-incompatibility.md)
## html2pdf
- [advanced.md](./html2pdf/advanced.md)
- [index.md](./html2pdf/index.md)
- [laravel.md](./html2pdf/laravel.md)
- [security.md](./html2pdf/security.md)
- [styling.md](./html2pdf/styling.md)
- [usage.md](./html2pdf/usage.md)
## icons
- [icon-system.md](./icons/icon-system.md)
## layouts
- [master.md](./layouts/master.md)
## legacy
- [architecture-rules-1.md](./legacy/architecture-rules-1.md)
- [architecture-rules.md](./legacy/architecture-rules.md)
- [bugfix-table-layout-action.md](./legacy/bugfix-table-layout-action.md)
- [filament-components-usage-1.md](./legacy/filament-components-usage-1.md)
- [filament-components-usage.md](./legacy/filament-components-usage.md)
- [filament-dropdown-avatar-usage.md](./legacy/filament-dropdown-avatar-usage.md)
- [flags-components-1.md](./legacy/flags-components-1.md)
- [flags-components.md](./legacy/flags-components.md)
- [mcp-integration.md](./legacy/mcp-integration.md)
- [paths-and-assets-1.md](./legacy/paths-and-assets-1.md)
- [paths-and-assets.md](./legacy/paths-and-assets.md)
## llm-wiki
- [agents.md](./llm-wiki/AGENTS.md)
- [index.md](./llm-wiki/index.md)
- [log.md](./llm-wiki/log.md)
## quality-analysis
- [ui-module-quality-report.md](./quality-analysis/ui-module-quality-report.md)
## raw
- [index.md](./raw/index.md)
## raw/root-import
- [api-1.md](./raw/root-import/api-1.md)
- [api.md](./raw/root-import/api.md)
- [blocks-1.md](./raw/root-import/blocks-1.md)
- [blocks.md](./raw/root-import/blocks.md)
- [carousel-slider-1.md](./raw/root-import/carousel-slider-1.md)
- [carousel-slider.md](./raw/root-import/carousel-slider.md)
- [changelog-1.md](./raw/root-import/changelog-1.md)
- [changelog-2.md](./raw/root-import/changelog-2.md)
- [changelog.md](./raw/root-import/changelog.md)
- [chunk-1.md](./raw/root-import/chunk-1.md)
- [chunk.md](./raw/root-import/chunk.md)
- [ci-1.md](./raw/root-import/ci-1.md)
- [ci.md](./raw/root-import/ci.md)
- [custom-firm-fields-1.md](./raw/root-import/custom-firm-fields-1.md)
- [custom-firm-fields.md](./raw/root-import/custom-firm-fields.md)
- [custom-theme-1.md](./raw/root-import/custom-theme-1.md)
- [custom-theme.md](./raw/root-import/custom-theme.md)
- [eav-1.md](./raw/root-import/eav-1.md)
- [eav.md](./raw/root-import/eav.md)
- [effetcts-1.md](./raw/root-import/effetcts-1.md)
- [effetcts.md](./raw/root-import/effetcts.md)
- [filament-1.md](./raw/root-import/filament-1.md)
- [filament.md](./raw/root-import/filament.md)
- [flip-cards-1.md](./raw/root-import/flip-cards-1.md)
- [flip-cards.md](./raw/root-import/flip-cards.md)
- [global-search-1.md](./raw/root-import/global-search-1.md)
- [global-search.md](./raw/root-import/global-search.md)
- [links-1.md](./raw/root-import/links-1.md)
- [links.md](./raw/root-import/links.md)
- [media-1.md](./raw/root-import/media-1.md)
- [media.md](./raw/root-import/media.md)
- [megamenu-1.md](./raw/root-import/megamenu-1.md)
- [megamenu.md](./raw/root-import/megamenu.md)
- [navbar-1.md](./raw/root-import/navbar-1.md)
- [navbar.md](./raw/root-import/navbar.md)
- [page-builder-1.md](./raw/root-import/page-builder-1.md)
- [page-builder.md](./raw/root-import/page-builder.md)
- [qrcode-1.md](./raw/root-import/qrcode-1.md)
- [qrcode.md](./raw/root-import/qrcode.md)
- [ratings-1.md](./raw/root-import/ratings-1.md)
- [ratings.md](./raw/root-import/ratings.md)
- [tailwind-themes-1.md](./raw/root-import/tailwind-themes-1.md)
- [tailwind-themes.md](./raw/root-import/tailwind-themes.md)
- [test-1.md](./raw/root-import/test-1.md)
- [test.md](./raw/root-import/test.md)
- [theme-1.md](./raw/root-import/theme-1.md)
- [theme.md](./raw/root-import/theme.md)
- [ubuntu-1.md](./raw/root-import/ubuntu-1.md)
- [ubuntu.md](./raw/root-import/ubuntu.md)
- [widgets-1.md](./raw/root-import/widgets-1.md)
- [widgets.md](./raw/root-import/widgets.md)
## roadmap
- [00-index-1.md](./roadmap/00-index-1.md)
- [00-index.md](./roadmap/00-index.md)
- [00-overview.md](./roadmap/00-overview.md)
- [01-current-state.md](./roadmap/01-current-state.md)
- [01-now.md](./roadmap/01-now.md)
- [02-goals.md](./roadmap/02-goals.md)
- [02-next.md](./roadmap/02-next.md)
- [03-later.md](./roadmap/03-later.md)
- [03-workstreams.md](./roadmap/03-workstreams.md)
- [04-milestones.md](./roadmap/04-milestones.md)
- [04-risks.md](./roadmap/04-risks.md)
- [05-risks.md](./roadmap/05-risks.md)
- [2025-q4-roadmap.md](./roadmap/2025-q4-roadmap.md)
- [bottlenecks.md](./roadmap/bottlenecks.md)
- [component-system-1.md](./roadmap/component-system-1.md)
- [component-system.md](./roadmap/component-system.md)
- [form-component-1.md](./roadmap/form-component-1.md)
- [form-component.md](./roadmap/form-component.md)
- [form-components.md](./roadmap/form-components.md)
- [legacy-roadmap.md](./roadmap/legacy-roadmap.md)
- [phases.md](./roadmap/phases.md)
- [q4-roadmap.md](./roadmap/q4-roadmap.md)
- [quality.md](./roadmap/quality.md)
- [roadmap-q4.md](./roadmap/roadmap-q4.md)
- [roadmap.md](./roadmap/roadmap.md)
- [theme-system-1.md](./roadmap/theme-system-1.md)
- [theme-system.md](./roadmap/theme-system.md)
- [vision.md](./roadmap/vision.md)
## roadmap/archive
- [component-system-1.md](./roadmap/archive/component-system-1.md)
- [component-system.md](./roadmap/archive/component-system.md)
- [form-component-1.md](./roadmap/archive/form-component-1.md)
- [form-component.md](./roadmap/archive/form-component.md)
- [theme-system-1.md](./roadmap/archive/theme-system-1.md)
- [theme-system.md](./roadmap/archive/theme-system.md)
## roadmap/legacy
- [legacy-roadmap-and-issues.md](./roadmap/legacy/legacy-roadmap-and-issues.md)
- [legacy-roadmap-conflict.md](./roadmap/legacy/legacy-roadmap-conflict.md)
- [legacy-roadmap.md](./roadmap/legacy/legacy-roadmap.md)
## roadmap/prior_cycles
- [component-system-1.md](./roadmap/prior_cycles/component-system-1.md)
- [component-system.md](./roadmap/prior_cycles/component-system.md)
- [form-component-1.md](./roadmap/prior_cycles/form-component-1.md)
- [form-component.md](./roadmap/prior_cycles/form-component.md)
- [theme-system-1.md](./roadmap/prior_cycles/theme-system-1.md)
- [theme-system.md](./roadmap/prior_cycles/theme-system.md)
## root
- [00-index-1.md](./00-index-1.md)
- [00-index.md](./00-index.md)
- [04-datas.md](./04-datas.md)
- [advanced-form-components-1.md](./advanced-form-components-1.md)
- [advanced-form-components.md](./advanced-form-components.md)
- [agent-confidence-discipline.md](./agent-confidence-discipline.md)
- [agent-confidence-protocol.md](./agent-confidence-protocol.md)
- [agent-edit-discipline.md](./agent-edit-discipline.md)
- [ai-methodologies.md](./ai-methodologies.md)
- [algolia-docsearch-1-1.md](./algolia-docsearch-1-1.md)
- [algolia-docsearch-1.md](./algolia-docsearch-1.md)
- [algolia-docsearch-2.md](./algolia-docsearch-2.md)
- [algolia-docsearch.md](./algolia-docsearch.md)
- [altro.md](./altro.md)
- [analysis.md](./analysis.md)
- [api.md](./api.md)
- [architecture-.md](./architecture-.md)
- [architecture-1.md](./architecture-1.md)
- [architecture-2025-1.md](./architecture-2025-1.md)
- [architecture-2025.md](./architecture-2025.md)
- [architecture-archive-1.md](./architecture-archive-1.md)
- [architecture-archive-2.md](./architecture-archive-2.md)
- [architecture-patterns.md](./architecture-patterns.md)
- [architecture-rules-1-1.md](./architecture-rules-1-1.md)
- [architecture-rules-1.md](./architecture-rules-1.md)
- [architecture-rules-2.md](./architecture-rules-2.md)
- [architecture-rules.md](./architecture-rules.md)
- [architecture.md](./architecture.md)
- [audit-columns-laraxot-compliance.md](./audit-columns-laraxot-compliance.md)
- [audit-models-migrations.md](./audit-models-migrations.md)
- [auth-pages-1.md](./auth-pages-1.md)
- [auth-pages.md](./auth-pages.md)
- [authentication.md](./authentication.md)
- [awstest-bugfix-undefined-variable.md](./awstest-bugfix-undefined-variable.md)
- [base-components-1.md](./base-components-1.md)
- [base-components.md](./base-components.md)
- [best-practices-1-1.md](./best-practices-1-1.md)
- [best-practices-1.md](./best-practices-1.md)
- [best-practices-2.md](./best-practices-2.md)
- [best-practices.md](./best-practices.md)
- [blade-components.md](./blade-components.md)
- [blade-data-handling-1-1.md](./blade-data-handling-1-1.md)
- [blade-data-handling-1.md](./blade-data-handling-1.md)
- [blade-data-handling-2.md](./blade-data-handling-2.md)
- [blade-data-handling.md](./blade-data-handling.md)
- [blade-icons.md](./blade-icons.md)
- [blocks-system-1-1.md](./blocks-system-1-1.md)
- [blocks-system-1.md](./blocks-system-1.md)
- [blocks-system-2.md](./blocks-system-2.md)
- [blocks-system.md](./blocks-system.md)
- [blocks.md](./blocks.md)
- [bottlenecks.md](./bottlenecks.md)
- [brands-icons-integration.md](./brands-icons-integration.md)
- [brands-icons.md](./brands-icons.md)
- [bugfix-address-field-hydration.md](./bugfix-address-field-hydration.md)
- [bugfix-awstest-undefined-variable.md](./bugfix-awstest-undefined-variable.md)
- [bugfix-icons-missing-.md](./bugfix-icons-missing-.md)
- [bugfix-icons-missing-1.md](./bugfix-icons-missing-1.md)
- [bugfix-icons-missing-2025-01-27.deprecated.md](./bugfix-icons-missing-2025-01-27.deprecated.md)
- [bugfix-icons-missing.md](./bugfix-icons-missing.md)
- [bugfix-table-layout-action-.md](./bugfix-table-layout-action-.md)
- [bugfix-table-layout-action-1.md](./bugfix-table-layout-action-1.md)
- [bugfix-table-layout-action-2025-01-27.deprecated.md](./bugfix-table-layout-action-2025-01-27.deprecated.md)
- [bugfix-table-layout-action-conflict.md](./bugfix-table-layout-action-conflict.md)
- [bugfix-table-layout-action.md](./bugfix-table-layout-action.md)
- [bugfix-table-layout-toggle-not-working.md](./bugfix-table-layout-toggle-not-working.md)
- [carousel-slider.md](./carousel-slider.md)
- [case-conflicts.md](./case-conflicts.md)
- [changelog.md](./CHANGELOG.md)
- [chart-components-1-1.md](./chart-components-1-1.md)
- [chart-components-1.md](./chart-components-1.md)
- [chart-components-2.md](./chart-components-2.md)
- [chart-components.md](./chart-components.md)
- [chunk.md](./chunk.md)
- [ci.md](./ci.md)
- [cms-link-1-1.md](./cms-link-1-1.md)
- [cms-link-1.md](./cms-link-1.md)
- [cms-link-2.md](./cms-link-2.md)
- [cms-link.md](./cms-link.md)
- [cms-themes-link-1-1.md](./cms-themes-link-1-1.md)
- [cms-themes-link-1.md](./cms-themes-link-1.md)
- [cms-themes-link-2.md](./cms-themes-link-2.md)
- [cms-themes-link.md](./cms-themes-link.md)
- [code-quality-analysis.md](./code-quality-analysis.md)
- [code-redundancy-audit.md](./code-redundancy-audit.md)
- [codex-error-fix.md](./codex-error-fix.md)
- [components-guide-1.md](./components-guide-1.md)
- [components-guide.md](./components-guide.md)
- [components.md](./components.md)
- [confidence-guidelines.md](./confidence-guidelines.md)
- [conflict-resolution-iconstatecolumn-1.md](./conflict-resolution-iconstatecolumn-1.md)
- [conflict-resolution-iconstatecolumn.md](./conflict-resolution-iconstatecolumn.md)
- [conflict-resolution-locationselector-1.md](./conflict-resolution-locationselector-1.md)
- [conflict-resolution-locationselector.md](./conflict-resolution-locationselector.md)
- [conflict-resolution-tablelayoutenum-1.md](./conflict-resolution-tablelayoutenum-1.md)
- [conflict-resolution-tablelayoutenum.md](./conflict-resolution-tablelayoutenum.md)
- [conflict-resolution-translation-files-1.md](./conflict-resolution-translation-files-1.md)
- [conflict-resolution-translation-files.md](./conflict-resolution-translation-files.md)
- [conflict-resolution.md](./conflict-resolution.md)
- [conflicts.md](./conflicts.md)
- [conflitti-merge-risolti-1.md](./conflitti-merge-risolti-1.md)
- [conflitti-merge-risolti.md](./conflitti-merge-risolti.md)
- [consolidation-plan.md](./consolidation-plan.md)
- [consolidation-script.md](./consolidation-script.md)
- [contracts-naming.md](./contracts-naming.md)
- [convenzioni-naming-campi-1-1.md](./convenzioni-naming-campi-1-1.md)
- [convenzioni-naming-campi-1.md](./convenzioni-naming-campi-1.md)
- [convenzioni-naming-campi-2.md](./convenzioni-naming-campi-2.md)
- [convenzioni-naming-campi.md](./convenzioni-naming-campi.md)
- [copilot-redundancy-audit-1.md](./copilot-redundancy-audit-1.md)
- [copilot-redundancy-audit-2026-05-25.deprecated.md](./copilot-redundancy-audit-2026-05-25.deprecated.md)
- [copilot-redundancy-audit.md](./copilot-redundancy-audit.md)
- [coverage.md](./coverage.md)
- [custom-404-page-1-1.md](./custom-404-page-1-1.md)
- [custom-404-page-1.md](./custom-404-page-1.md)
- [custom-404-page-2.md](./custom-404-page-2.md)
- [custom-404-page.md](./custom-404-page.md)
- [custom-firm-fields.md](./custom-firm-fields.md)
- [custom-theme.md](./custom-theme.md)
- [customizing-your-site-1-1.md](./customizing-your-site-1-1.md)
- [customizing-your-site-1.md](./customizing-your-site-1.md)
- [customizing-your-site-2.md](./customizing-your-site-2.md)
- [customizing-your-site.md](./customizing-your-site.md)
- [cyclomatic-complexity-report.md](./cyclomatic-complexity-report.md)
- [data-display-components-1.md](./data-display-components-1.md)
- [data-display-components.md](./data-display-components.md)
- [datas-not-dtos-convention.md](./datas-not-dtos-convention.md)
- [dependencies.md](./dependencies.md)
- [dependency-intelligence.md](./dependency-intelligence.md)
- [dependency-rules.md](./dependency-rules.md)
- [design-comuni-faq-components.md](./design-comuni-faq-components.md)
- [design-comuni-implementation.md](./design-comuni-implementation.md)
- [design-system-1-1.md](./design-system-1-1.md)
- [design-system-1.md](./design-system-1.md)
- [design-system-2.md](./design-system-2.md)
- [design-system.md](./design-system.md)
- [development-workflow-rules.md](./development-workflow-rules.md)
- [disabled-components.md](./disabled-components.md)
- [docs-archive-policy.md](./docs-archive-policy.md)
- [docs-health.md](./docs-health.md)
- [dry-kiss-analysis-.md](./dry-kiss-analysis-.md)
- [dry-kiss-analysis-1.md](./dry-kiss-analysis-1.md)
- [dry-kiss-analysis-2025-10-15.deprecated.md](./dry-kiss-analysis-2025-10-15.deprecated.md)
- [dry-kiss-analysis-conflict.md](./dry-kiss-analysis-conflict.md)
- [dry-kiss-analysis.md](./dry-kiss-analysis.md)
- [duplicate-methods-analysis.md](./duplicate-methods-analysis.md)
- [duplicate-methods-report.md](./duplicate-methods-report.md)
- [duplicate-methods.md](./duplicate-methods.md)
- [eav.md](./eav.md)
- [effetcts.md](./effetcts.md)
- [eloquent-isset-vs-property-exists.md](./eloquent-isset-vs-property-exists.md)
- [eloquent-properties-isset-vs-property-exists.md](./eloquent-properties-isset-vs-property-exists.md)
- [enum-transclass-implementation.md](./enum-transclass-implementation.md)
- [feedback-components-1.md](./feedback-components-1.md)
- [feedback-components.md](./feedback-components.md)
- [filament-4x-upgrade-1.md](./filament-4x-upgrade-1.md)
- [filament-4x-upgrade-report-1.md](./filament-4x-upgrade-report-1.md)
- [filament-4x-upgrade-report.md](./filament-4x-upgrade-report.md)
- [filament-4x-upgrade.md](./filament-4x-upgrade.md)
- [filament-5x-compatibility.md](./filament-5x-compatibility.md)
- [filament-blade-components-usage-1-1.md](./filament-blade-components-usage-1-1.md)
- [filament-blade-components-usage-1.md](./filament-blade-components-usage-1.md)
- [filament-blade-components-usage-2.md](./filament-blade-components-usage-2.md)
- [filament-blade-components-usage.md](./filament-blade-components-usage.md)
- [filament-components-1-1.md](./filament-components-1-1.md)
- [filament-components-1.md](./filament-components-1.md)
- [filament-components-2.md](./filament-components-2.md)
- [filament-components-errors-1-1.md](./filament-components-errors-1-1.md)
- [filament-components-errors-1.md](./filament-components-errors-1.md)
- [filament-components-errors-2.md](./filament-components-errors-2.md)
- [filament-components-errors.md](./filament-components-errors.md)
- [filament-components-location-studio-1-1.md](./filament-components-location-studio-1-1.md)
- [filament-components-location-studio-1.md](./filament-components-location-studio-1.md)
- [filament-components-location-studio-2.md](./filament-components-location-studio-2.md)
- [filament-components-location-studio.md](./filament-components-location-studio.md)
- [filament-components-usage-1-1.md](./filament-components-usage-1-1.md)
- [filament-components-usage-1.md](./filament-components-usage-1.md)
- [filament-components-usage-2.md](./filament-components-usage-2.md)
- [filament-components-usage.md](./filament-components-usage.md)
- [filament-components.md](./filament-components.md)
- [filament-custom-columns-relationship-resolution.md](./filament-custom-columns-relationship-resolution.md)
- [filament-dropdown-avatar-components-1.md](./filament-dropdown-avatar-components-1.md)
- [filament-dropdown-avatar-components.md](./filament-dropdown-avatar-components.md)
- [filament-dropdown-avatar-usage-1-1.md](./filament-dropdown-avatar-usage-1-1.md)
- [filament-dropdown-avatar-usage-1.md](./filament-dropdown-avatar-usage-1.md)
- [filament-dropdown-avatar-usage-2.md](./filament-dropdown-avatar-usage-2.md)
- [filament-dropdown-avatar-usage.md](./filament-dropdown-avatar-usage.md)
- [filament-error-fileupload-buttonlabel-1-1.md](./filament-error-fileupload-buttonlabel-1-1.md)
- [filament-error-fileupload-buttonlabel-1.md](./filament-error-fileupload-buttonlabel-1.md)
- [filament-error-fileupload-buttonlabel-2.md](./filament-error-fileupload-buttonlabel-2.md)
- [filament-error-fileupload-buttonlabel.md](./filament-error-fileupload-buttonlabel.md)
- [filament-error-fileupload-icon-1-1.md](./filament-error-fileupload-icon-1-1.md)
- [filament-error-fileupload-icon-1.md](./filament-error-fileupload-icon-1.md)
- [filament-error-fileupload-icon-2.md](./filament-error-fileupload-icon-2.md)
- [filament-error-fileupload-icon.md](./filament-error-fileupload-icon.md)
- [filament-error-fileupload-prefixicon-1-1.md](./filament-error-fileupload-prefixicon-1-1.md)
- [filament-error-fileupload-prefixicon-1.md](./filament-error-fileupload-prefixicon-1.md)
- [filament-error-fileupload-prefixicon-2.md](./filament-error-fileupload-prefixicon-2.md)
- [filament-error-fileupload-prefixicon.md](./filament-error-fileupload-prefixicon.md)
- [filament-extension-fixes.md](./filament-extension-fixes.md)
- [filament-fileupload-1-1.md](./filament-fileupload-1-1.md)
- [filament-fileupload-1.md](./filament-fileupload-1.md)
- [filament-fileupload-2.md](./filament-fileupload-2.md)
- [filament-fileupload-components-1-1.md](./filament-fileupload-components-1-1.md)
- [filament-fileupload-components-1.md](./filament-fileupload-components-1.md)
- [filament-fileupload-components-2.md](./filament-fileupload-components-2.md)
- [filament-fileupload-components.md](./filament-fileupload-components.md)
- [filament-fileupload.md](./filament-fileupload.md)
- [filament-groupcolumn-and-custom-columns.md](./filament-groupcolumn-and-custom-columns.md)
- [filament-pages-refactoring-1.md](./filament-pages-refactoring-1.md)
- [filament-pages-refactoring.md](./filament-pages-refactoring.md)
- [filament-pages-structure.md](./filament-pages-structure.md)
- [filament-resources-structure-1-1.md](./filament-resources-structure-1-1.md)
- [filament-resources-structure-1.md](./filament-resources-structure-1.md)
- [filament-resources-structure-2.md](./filament-resources-structure-2.md)
- [filament-resources-structure.md](./filament-resources-structure.md)
- [filament-v4-theme-upgrade.md](./filament-v4-theme-upgrade.md)
- [filament-version.md](./filament-version.md)
- [filament-vscode-1-1.md](./filament-vscode-1-1.md)
- [filament-vscode-1.md](./filament-vscode-1.md)
- [filament-vscode-2.md](./filament-vscode-2.md)
- [filament-vscode.md](./filament-vscode.md)
- [filament-widgets-frontend.md](./filament-widgets-frontend.md)
- [filament.md](./filament.md)
- [filamentropdown-avatar-components.md](./filamentropdown-avatar-components.md)
- [filamentropdown-avatar-usage.md](./filamentropdown-avatar-usage.md)
- [file-naming-rules.md](./file-naming-rules.md)
- [filosofia-modulo-ui.md](./filosofia-modulo-ui.md)
- [flags-components-1-1.md](./flags-components-1-1.md)
- [flags-components-1.md](./flags-components-1.md)
- [flags-components-2.md](./flags-components-2.md)
- [flags-components.md](./flags-components.md)
- [flip-cards.md](./flip-cards.md)
- [folio-volt-best-practices.md](./folio-volt-best-practices.md)
- [form-components-1-1.md](./form-components-1-1.md)
- [form-components-1.md](./form-components-1.md)
- [form-components-2.md](./form-components-2.md)
- [form-components.md](./form-components.md)
- [form-filament-widgets-1.md](./form-filament-widgets-1.md)
- [form-filament-widgets.md](./form-filament-widgets.md)
- [frontend.md](./frontend.md)
- [full-calendar.md](./full-calendar.md)
- [geo-boundary.md](./geo-boundary.md)
- [geo-dependency-violation-interactive-map.md](./geo-dependency-violation-interactive-map.md)
- [getting-started-1-1.md](./getting-started-1-1.md)
- [getting-started-1.md](./getting-started-1.md)
- [getting-started-2.md](./getting-started-2.md)
- [getting-started.md](./getting-started.md)
- [git-conflicts-inventory.md](./git-conflicts-inventory.md)
- [git-conflicts-resolution-summary.md](./git-conflicts-resolution-summary.md)
- [git-conflicts-resolution-sumy.md](./git-conflicts-resolution-sumy.md)
- [global-search.md](./global-search.md)
- [group-column-fix.md](./group-column-fix.md)
- [groupcolumn-relationship-resolution-analysis.md](./groupcolumn-relationship-resolution-analysis.md)
- [groupcolumn.md](./groupcolumn.md)
- [icon-state-column-business-logic.md](./icon-state-column-business-logic.md)
- [icon-system.md](./icon-system.md)
- [icons.md](./icons.md)
- [iconstatesplitcolumn-actions-implementation-1.md](./iconstatesplitcolumn-actions-implementation-1.md)
- [iconstatesplitcolumn-actions-implementation.md](./iconstatesplitcolumn-actions-implementation.md)
- [iconstatesplitcolumn-implementation-1-1.md](./iconstatesplitcolumn-implementation-1-1.md)
- [iconstatesplitcolumn-implementation-1.md](./iconstatesplitcolumn-implementation-1.md)
- [iconstatesplitcolumn-implementation-2.md](./iconstatesplitcolumn-implementation-2.md)
- [iconstatesplitcolumn-implementation.md](./iconstatesplitcolumn-implementation.md)
- [index.md](./index.md)
- [infolist-schema-guidelines-1.md](./infolist-schema-guidelines-1.md)
- [infolist-schema-guidelines.md](./infolist-schema-guidelines.md)
- [inline-date-picker-1-1.md](./inline-date-picker-1-1.md)
- [inline-date-picker-1.md](./inline-date-picker-1.md)
- [inline-date-picker-2.md](./inline-date-picker-2.md)
- [inline-date-picker.md](./inline-date-picker.md)
- [inlineate-picker.md](./inlineate-picker.md)
- [internal-debate-psr4-resolution.md](./internal-debate-psr4-resolution.md)
- [italian-language-corrections-1.md](./italian-language-corrections-1.md)
- [italian-language-corrections.md](./italian-language-corrections.md)
- [keting-components-implementation.md](./keting-components-implementation.md)
- [lang-link-1-1.md](./lang-link-1-1.md)
- [lang-link-1.md](./lang-link-1.md)
- [lang-link-2.md](./lang-link-2.md)
- [lang-link.md](./lang-link.md)
- [laravel-13-upgrade.md](./laravel-13-upgrade.md)
- [launch-plan.md](./launch-plan.md)
- [layout-components-1.md](./layout-components-1.md)
- [layout-components.md](./layout-components.md)
- [layouts-and-themes-1-1.md](./layouts-and-themes-1-1.md)
- [layouts-and-themes-1.md](./layouts-and-themes-1.md)
- [layouts-and-themes-2.md](./layouts-and-themes-2.md)
- [layouts-and-themes.md](./layouts-and-themes.md)
- [links.md](./links.md)
- [localization.md](./localization.md)
- [map-integration-guide.md](./map-integration-guide.md)
- [marketing-components-implementation.md](./marketing-components-implementation.md)
- [mcp-configuration.md](./mcp-configuration.md)
- [mcp-integration-1.md](./mcp-integration-1.md)
- [mcp-integration.md](./mcp-integration.md)
- [mcp-server-recommended-1.md](./mcp-server-recommended-1.md)
- [mcp-server-recommended.md](./mcp-server-recommended.md)
- [mcp-ui-ux.md](./mcp-ui-ux.md)
- [media.md](./media.md)
- [megamenu.md](./megamenu.md)
- [merge-conflict-files-list.md](./merge-conflict-files-list.md)
- [merge-conflicts-list.md](./merge-conflicts-list.md)
- [metodi-duplicati-analisi-1.md](./metodi-duplicati-analisi-1.md)
- [metodi-duplicati-analisi-2.md](./metodi-duplicati-analisi-2.md)
- [metodi-duplicati-analisi-3.md](./metodi-duplicati-analisi-3.md)
- [metodi-duplicati-analisi.md](./metodi-duplicati-analisi.md)
- [migrations.md](./migrations.md)
- [models-factory-seeder-analysis.md](./models-factory-seeder-analysis.md)
- [modularity-optimizations.md](./modularity-optimizations.md)
- [module-analysis-complete.md](./module-analysis-complete.md)
- [module-analysis.md](./module-analysis.md)
- [module-icons-design-system.md](./module-icons-design-system.md)
- [module-ui-1.md](./module-ui-1.md)
- [module-ui.md](./module-ui.md)
- [naming-conventions-1-1.md](./naming-conventions-1-1.md)
- [naming-conventions-1.md](./naming-conventions-1.md)
- [naming-conventions-2.md](./naming-conventions-2.md)
- [naming-conventions.md](./naming-conventions.md)
- [naming-rules-1-1.md](./naming-rules-1-1.md)
- [naming-rules-1.md](./naming-rules-1.md)
- [naming-rules-2.md](./naming-rules-2.md)
- [naming-rules.md](./naming-rules.md)
- [navbar.md](./navbar.md)
- [navigation-components-1-1.md](./navigation-components-1-1.md)
- [navigation-components-1.md](./navigation-components-1.md)
- [navigation-components-2.md](./navigation-components-2.md)
- [navigation-components.md](./navigation-components.md)
- [navigation.md](./navigation.md)
- [nestedset-migration-best-practices.md](./nestedset-migration-best-practices.md)
- [never-use-label-rule-1.md](./never-use-label-rule-1.md)
- [never-use-label-rule.md](./never-use-label-rule.md)
- [no-svg-hardcoded-in-blade.md](./no-svg-hardcoded-in-blade.md)
- [on-demand-pattern.md](./on-demand-pattern.md)
- [opening-hours-rule-localization-1.md](./opening-hours-rule-localization-1.md)
- [opening-hours-rule-localization.md](./opening-hours-rule-localization.md)
- [opening-hours-translation-fix-1.md](./opening-hours-translation-fix-1.md)
- [opening-hours-translation-fix.md](./opening-hours-translation-fix.md)
- [optimization-analysis-dry-kiss.md](./optimization-analysis-dry-kiss.md)
- [optimization-analysis.md](./optimization-analysis.md)
- [optimization-recommendations-1.md](./optimization-recommendations-1.md)
- [optimization-recommendations.md](./optimization-recommendations.md)
- [ottimizzazioni-approfondite-modulo-ui.md](./ottimizzazioni-approfondite-modulo-ui.md)
- [ottimizzazioni-modulo-ui.md](./ottimizzazioni-modulo-ui.md)
- [ottimizzazioni-super-dry-kiss.md](./ottimizzazioni-super-dry-kiss.md)
- [overview-extended.md](./overview-extended.md)
- [packages.md](./packages.md)
- [page-builder.md](./page-builder.md)
- [paths-and-assets-1-1.md](./paths-and-assets-1-1.md)
- [paths-and-assets-1.md](./paths-and-assets-1.md)
- [paths-and-assets-2.md](./paths-and-assets-2.md)
- [paths-and-assets.md](./paths-and-assets.md)
- [performance-optimization.md](./performance-optimization.md)
- [philosophy.md](./philosophy.md)
- [phpmd-improvements.md](./phpmd-improvements.md)
- [phpstan-compliance-status.md](./phpstan-compliance-status.md)
- [phpstan-compliance.md](./phpstan-compliance.md)
- [phpstan-corrections-.md](./phpstan-corrections-.md)
- [phpstan-corrections-archive-1.md](./phpstan-corrections-archive-1.md)
- [phpstan-corrections-final.md](./phpstan-corrections-final.md)
- [phpstan-corrections-gennaio-.md](./phpstan-corrections-gennaio-.md)
- [phpstan-corrections-gennaio-2025.md](./phpstan-corrections-gennaio-2025.md)
- [phpstan-corrections-gennaio-archive-1.md](./phpstan-corrections-gennaio-archive-1.md)
- [phpstan-corrections-gennaio.md](./phpstan-corrections-gennaio.md)
- [phpstan-corrections-january-.md](./phpstan-corrections-january-.md)
- [phpstan-corrections-january-archive-1.md](./phpstan-corrections-january-archive-1.md)
- [phpstan-corrections-january.md](./phpstan-corrections-january.md)
- [phpstan-corrections-renamed.md](./phpstan-corrections-renamed.md)
- [phpstan-corrections-summary-1.md](./phpstan-corrections-summary-1.md)
- [phpstan-corrections-summary.md](./phpstan-corrections-summary.md)
- [phpstan-corrections-sumy.md](./phpstan-corrections-sumy.md)
- [phpstan-corrections.md](./phpstan-corrections.md)
- [phpstan-error-analysis-strategy.md](./phpstan-error-analysis-strategy.md)
- [phpstan-error-analysis.md](./phpstan-error-analysis.md)
- [phpstan-errors-locationselector.md](./phpstan-errors-locationselector.md)
- [phpstan-errors-resolution.md](./phpstan-errors-resolution.md)
- [phpstan-errors-roadmap.md](./phpstan-errors-roadmap.md)
- [phpstan-fixes-.md](./phpstan-fixes-.md)
- [phpstan-fixes-1-1.md](./phpstan-fixes-1-1.md)
- [phpstan-fixes-1.md](./phpstan-fixes-1.md)
- [phpstan-fixes-2-1.md](./phpstan-fixes-2-1.md)
- [phpstan-fixes-2.md](./phpstan-fixes-2.md)
- [phpstan-fixes-2025-1.md](./phpstan-fixes-2025-1.md)
- [phpstan-fixes-2025.md](./phpstan-fixes-2025.md)
- [phpstan-fixes-3.md](./phpstan-fixes-3.md)
- [phpstan-fixes-archive-1.md](./phpstan-fixes-archive-1.md)
- [phpstan-fixes-archive-2.md](./phpstan-fixes-archive-2.md)
- [phpstan-fixes-archive-3.md](./phpstan-fixes-archive-3.md)
- [phpstan-fixes-archive-4.md](./phpstan-fixes-archive-4.md)
- [phpstan-fixes-archive-5.md](./phpstan-fixes-archive-5.md)
- [phpstan-fixes-conflict-d41d8c.md](./phpstan-fixes-conflict-d41d8c.md)
- [phpstan-fixes-conflict.md](./phpstan-fixes-conflict.md)
- [phpstan-fixes-gennaio-.md](./phpstan-fixes-gennaio-.md)
- [phpstan-fixes-gennaio-2025.md](./phpstan-fixes-gennaio-2025.md)
- [phpstan-fixes-gennaio-archive-1.md](./phpstan-fixes-gennaio-archive-1.md)
- [phpstan-fixes-gennaio.md](./phpstan-fixes-gennaio.md)
- [phpstan-fixes-january-.md](./phpstan-fixes-january-.md)
- [phpstan-fixes-january-1-1.md](./phpstan-fixes-january-1-1.md)
- [phpstan-fixes-january-1-archive-1.md](./phpstan-fixes-january-1-archive-1.md)
- [phpstan-fixes-january-1.md](./phpstan-fixes-january-1.md)
- [phpstan-fixes-january-2025.md](./phpstan-fixes-january-2025.md)
- [phpstan-fixes-january-archive-1.md](./phpstan-fixes-january-archive-1.md)
- [phpstan-fixes-january.md](./phpstan-fixes-january.md)
- [phpstan-fixes-november-.md](./phpstan-fixes-november-.md)
- [phpstan-fixes-november-2025.md](./phpstan-fixes-november-2025.md)
- [phpstan-fixes-november-archive-1.md](./phpstan-fixes-november-archive-1.md)
- [phpstan-fixes-november.md](./phpstan-fixes-november.md)
- [phpstan-fixes-summary.md](./phpstan-fixes-summary.md)
- [phpstan-fixes-sumy.md](./phpstan-fixes-sumy.md)
- [phpstan-fixes.md](./phpstan-fixes.md)
- [phpstan-level-10-cleanup.md](./phpstan-level-10-cleanup.md)
- [phpstan-level-10-compliance.md](./phpstan-level-10-compliance.md)
- [phpstan-level10-bugfixes-comprehensive.md](./phpstan-level10-bugfixes-comprehensive.md)
- [phpstan-namespace-correction-1.md](./phpstan-namespace-correction-1.md)
- [phpstan-namespace-correction.md](./phpstan-namespace-correction.md)
- [phpstan-patterns.md](./phpstan-patterns.md)
- [phpstan-property-exists-elimination.md](./phpstan-property-exists-elimination.md)
- [phpstan-radio-badge-fix-1-1.md](./phpstan-radio-badge-fix-1-1.md)
- [phpstan-radio-badge-fix-1.md](./phpstan-radio-badge-fix-1.md)
- [phpstan-radio-badge-fix-2.md](./phpstan-radio-badge-fix-2.md)
- [phpstan-radio-badge-fix.md](./phpstan-radio-badge-fix.md)
- [phpstan-roadmap.md](./phpstan-roadmap.md)
- [phpstan-status.md](./phpstan-status.md)
- [phpstan.md](./phpstan.md)
- [ponytail-audit-over-engineering.md](./ponytail-audit-over-engineering.md)
- [prd.md](./prd.md)
- [product-launch-plan-1.md](./product-launch-plan-1.md)
- [product-launch-plan.md](./product-launch-plan.md)
- [product-requirements.md](./product-requirements.md)
- [product-roadmap-1.md](./product-roadmap-1.md)
- [product-roadmap.md](./product-roadmap.md)
- [product-strategy-1.md](./product-strategy-1.md)
- [product-strategy.md](./product-strategy.md)
- [project-structure.md](./project-structure.md)
- [prompt-rules-link-1.md](./prompt-rules-link-1.md)
- [prompt-rules-link.md](./prompt-rules-link.md)
- [psr4-autoloading-error-analysis.md](./psr4-autoloading-error-analysis.md)
- [psr4-fix-implementation-plan.md](./psr4-fix-implementation-plan.md)
- [psr4-namespace-violations.md](./psr4-namespace-violations.md)
- [public-resources-management-1-1.md](./public-resources-management-1-1.md)
- [public-resources-management-1.md](./public-resources-management-1.md)
- [public-resources-management-2.md](./public-resources-management-2.md)
- [public-resources-management.md](./public-resources-management.md)
- [qmd-setup.md](./qmd-setup.md)
- [qrcode.md](./qrcode.md)
- [quality-report.md](./quality-report.md)
- [radio-collection-component-1.md](./radio-collection-component-1.md)
- [radio-collection-component.md](./radio-collection-component.md)
- [ratings.md](./ratings.md)
- [readme-en.md](./readme-en.md)
- [readme.md](./README.md)
- [redundancy-analysis.md](./redundancy-analysis.md)
- [redundancy-audit-1.md](./redundancy-audit-1.md)
- [redundancy-audit-2026-05-21.deprecated.md](./redundancy-audit-2026-05-21.deprecated.md)
- [redundancy-audit.md](./redundancy-audit.md)
- [redundancy-report.md](./redundancy-report.md)
- [release-marketing-standard.md](./release-marketing-standard.md)
- [resolution-conflitti-tablelayouttrait.md](./resolution-conflitti-tablelayouttrait.md)
- [risoluzione-conflitti-tablelayouttrait-1.md](./risoluzione-conflitti-tablelayouttrait-1.md)
- [risoluzione-conflitti-tablelayouttrait.md](./risoluzione-conflitti-tablelayouttrait.md)
- [roadmap-.md](./roadmap-.md)
- [roadmap-2025.md](./roadmap-2025.md)
- [roadmap-and-issues.md](./roadmap-and-issues.md)
- [roadmap-archive-1.md](./roadmap-archive-1.md)
- [roadmap-conflict.md](./roadmap-conflict.md)
- [roadmap.md](./roadmap.md)
- [root-file-policy.md](./root-file-policy.md)
- [root-files-hygiene.md](./root-files-hygiene.md)
- [rules-index.md](./rules-index.md)
- [rules-testing-no-migrate-fresh.md](./rules-testing-no-migrate-fresh.md)
- [s3test-bugfix-null-errorcode.md](./s3test-bugfix-null-errorcode.md)
- [s3test-critical-errors-analysis.md](./s3test-critical-errors-analysis.md)
- [s3test-method-duplication-bugfix.md](./s3test-method-duplication-bugfix.md)
- [s3test.md](./s3test.md)
- [schema.md](./schema.md)
- [second-brain.md](./second-brain.md)
- [selectstatecolumn-confirmation-modal-1-1.md](./selectstatecolumn-confirmation-modal-1-1.md)
- [selectstatecolumn-confirmation-modal-1.md](./selectstatecolumn-confirmation-modal-1.md)
- [selectstatecolumn-confirmation-modal-2.md](./selectstatecolumn-confirmation-modal-2.md)
- [selectstatecolumn-confirmation-modal.md](./selectstatecolumn-confirmation-modal.md)
- [selectstatecolumn.md](./selectstatecolumn.md)
- [spatie-media-library-migration-1-1.md](./spatie-media-library-migration-1-1.md)
- [spatie-media-library-migration-1.md](./spatie-media-library-migration-1.md)
- [spatie-media-library-migration-2.md](./spatie-media-library-migration-2.md)
- [spatie-media-library-migration.md](./spatie-media-library-migration.md)
- [sprint-planning-1.md](./sprint-planning-1.md)
- [sprint-planning-meeting.md](./sprint-planning-meeting.md)
- [sprint-planning.md](./sprint-planning.md)
- [state-transitions-1.md](./state-transitions-1.md)
- [state-transitions.md](./state-transitions.md)
- [strategy.md](./strategy.md)
- [strict-types-implementation-1.md](./strict-types-implementation-1.md)
- [strict-types-implementation.md](./strict-types-implementation.md)
- [structure.md](./structure.md)
- [struttura-themes-folio-1.md](./struttura-themes-folio-1.md)
- [struttura-themes-folio.md](./struttura-themes-folio.md)
- [studio-card-selector-implementation-1-1.md](./studio-card-selector-implementation-1-1.md)
- [studio-card-selector-implementation-1.md](./studio-card-selector-implementation-1.md)
- [studio-card-selector-implementation-2.md](./studio-card-selector-implementation-2.md)
- [studio-card-selector-implementation.md](./studio-card-selector-implementation.md)
- [svg-icons-automatic-registration.md](./svg-icons-automatic-registration.md)
- [svg-icons-complete.md](./svg-icons-complete.md)
- [table-components-1-1.md](./table-components-1-1.md)
- [table-components-1.md](./table-components-1.md)
- [table-components-2.md](./table-components-2.md)
- [table-components.md](./table-components.md)
- [table-layout-enum-analysis-1.md](./table-layout-enum-analysis-1.md)
- [table-layout-enum-analysis.md](./table-layout-enum-analysis.md)
- [table-layout-enum-complete-guide.md](./table-layout-enum-complete-guide.md)
- [table-layout-enum-comprehensive.md](./table-layout-enum-comprehensive.md)
- [table-layout-enum-implementation-example-1.md](./table-layout-enum-implementation-example-1.md)
- [table-layout-enum-implementation-example.md](./table-layout-enum-implementation-example.md)
- [table-layout-enum-usage-1-1.md](./table-layout-enum-usage-1-1.md)
- [table-layout-enum-usage-1.md](./table-layout-enum-usage-1.md)
- [table-layout-enum-usage-2.md](./table-layout-enum-usage-2.md)
- [table-layout-enum-usage.md](./table-layout-enum-usage.md)
- [tailwind-themes.md](./tailwind-themes.md)
- [task-consolidare-documentazione.md](./task-consolidare-documentazione.md)
- [task-location-selector.md](./task-location-selector.md)
- [task-ridurre-phpstan-suppressioni.md](./task-ridurre-phpstan-suppressioni.md)
- [task-test-livewire-components.md](./task-test-livewire-components.md)
- [test-conflicts-resolution-1.md](./test-conflicts-resolution-1.md)
- [test-conflicts-resolution.md](./test-conflicts-resolution.md)
- [test-fix-philosophy.md](./test-fix-philosophy.md)
- [test-structure-cleanup.md](./test-structure-cleanup.md)
- [test.md](./test.md)
- [testing-rules.md](./testing-rules.md)
- [testing.md](./testing.md)
- [theme-build.md](./theme-build.md)
- [theme-translation-sync.md](./theme-translation-sync.md)
- [theme-widget-translations.md](./theme-widget-translations.md)
- [theme.md](./theme.md)
- [transclass-rule-1.md](./transclass-rule-1.md)
- [transclass-rule.md](./transclass-rule.md)
- [translations-update-archive-1.md](./translations-update-archive-1.md)
- [translations-update-january-2026.md](./translations-update-january-2026.md)
- [translations-update-january.md](./translations-update-january.md)
- [translations-update-renamed.md](./translations-update-renamed.md)
- [translations-update.md](./translations-update.md)
- [translations.md](./translations.md)
- [troubleshooting.md](./troubleshooting.md)
- [ubuntu.md](./ubuntu.md)
- [ui-module.md](./ui-module.md)
- [ui-table-layout-enum.md](./ui-table-layout-enum.md)
- [ui.md](./ui.md)
- [user-research-1.md](./user-research-1.md)
- [user-research.md](./user-research.md)
- [validation-files-multilingua-1.md](./validation-files-multilingua-1.md)
- [validation-files-multilingua.md](./validation-files-multilingua.md)
- [vscode-filament-extension-1.md](./vscode-filament-extension-1.md)
- [vscode-filament-extension.md](./vscode-filament-extension.md)
- [vscode-filament-plugin-1-1.md](./vscode-filament-plugin-1-1.md)
- [vscode-filament-plugin-1.md](./vscode-filament-plugin-1.md)
- [vscode-filament-plugin-2.md](./vscode-filament-plugin-2.md)
- [vscode-filament-plugin.md](./vscode-filament-plugin.md)
- [vscode-php-setup-1.md](./vscode-php-setup-1.md)
- [vscode-php-setup.md](./vscode-php-setup.md)
- [widget-optimization-1.md](./widget-optimization-1.md)
- [widget-optimization.md](./widget-optimization.md)
- [widgets.md](./widgets.md)
## root-md-files
- [api-relocated.md](./root-md-files/api-relocated.md)
- [api.md](./root-md-files/api.md)
- [blocks-relocated.md](./root-md-files/blocks-relocated.md)
- [blocks.md](./root-md-files/blocks.md)
- [carousel-slider.md](./root-md-files/carousel-slider.md)
- [changelog.md](./root-md-files/CHANGELOG.md)
- [chunk.md](./root-md-files/chunk.md)
- [ci.md](./root-md-files/ci.md)
- [custom-firm-fields.md](./root-md-files/custom-firm-fields.md)
- [custom-theme.md](./root-md-files/custom-theme.md)
- [eav.md](./root-md-files/eav.md)
- [effetcts.md](./root-md-files/effetcts.md)
- [filament.md](./root-md-files/filament.md)
- [flip-cards.md](./root-md-files/flip-cards.md)
- [global-search.md](./root-md-files/global-search.md)
- [links.md](./root-md-files/links.md)
- [media.md](./root-md-files/media.md)
- [megamenu.md](./root-md-files/megamenu.md)
- [navbar.md](./root-md-files/navbar.md)
- [page-builder.md](./root-md-files/page-builder.md)
- [qrcode.md](./root-md-files/qrcode.md)
- [ratings.md](./root-md-files/ratings.md)
- [tailwind-themes.md](./root-md-files/tailwind-themes.md)
- [test.md](./root-md-files/test.md)
- [theme.md](./root-md-files/theme.md)
- [ubuntu.md](./root-md-files/ubuntu.md)
- [widgets.md](./root-md-files/widgets.md)
## standards
- [accessibility.md](./standards/accessibility.md)
- [auth-form-standards-1.md](./standards/auth-form-standards-1.md)
- [auth-form-standards.md](./standards/auth-form-standards.md)
- [form-standards-1.md](./standards/form-standards-1.md)
- [form-standards.md](./standards/form-standards.md)
- [performance.md](./standards/performance.md)
- [ui-standards.md](./standards/ui-standards.md)
## tasks
- [001-design-system-components.md](./tasks/001-design-system-components.md)
- [cleanup-redundant-files.md](./tasks/cleanup-redundant-files.md)
- [filament-v5-alignment.md](./tasks/filament-v5-alignment.md)
- [increase-test-coverage.md](./tasks/increase-test-coverage.md)
- [refactor-complex-components.md](./tasks/refactor-complex-components.md)
- [tasks-index.md](./tasks/tasks-index.md)
- [ui-cleanup-docs.md](./tasks/ui-cleanup-docs.md)
- [ui-filament-v5.md](./tasks/ui-filament-v5.md)
## testing
- [pest-testing-guide.md](./testing/pest-testing-guide.md)
## themes
- [asset-management-1.md](./themes/asset-management-1.md)
- [asset-management.md](./themes/asset-management.md)
- [compilation.md](./themes/compilation.md)
- [components.md](./themes/components.md)
- [optimizations.md](./themes/optimizations.md)
- [schemaless-attributes-guide.md](./themes/schemaless-attributes-guide.md)
## translations
- [lang-service-provider.md](./translations/lang-service-provider.md)
## wiki
- [agents.md](./wiki/AGENTS.md)
- [bmad-method.md](./wiki/bmad-method.md)
- [context-compression.md](./wiki/context-compression.md)
- [index.md](./wiki/index.md)
- [log.md](./wiki/log.md)
- [overview.md](./wiki/overview.md)
## wiki/concepts
- [auth-register-focus-loss-overlay.md](./wiki/concepts/auth-register-focus-loss-overlay.md)
- [block-rendering-and-optional-services.md](./wiki/concepts/block-rendering-and-optional-services.md)
- [claude-audit-static.md](./wiki/concepts/claude-audit-static.md)
- [code-redundancy-ui.md](./wiki/concepts/code-redundancy-ui.md)
- [context-overflow-prevention.md](./wiki/concepts/context-overflow-prevention.md)
- [enum-select-best-practices.md](./wiki/concepts/enum-select-best-practices.md)
- [enum-select-component.md](./wiki/concepts/enum-select-component.md)
- [enum-select-contract-and-false-friends.md](./wiki/concepts/enum-select-contract-and-false-friends.md)
- [enum-select-usage.md](./wiki/concepts/enum-select-usage.md)
- [enumselect-filament-api-collisions.md](./wiki/concepts/enumselect-filament-api-collisions.md)
- [filament-first-blade-canonical.md](./wiki/concepts/filament-first-blade-canonical.md)
- [method-name-homonyms.md](./wiki/concepts/method-name-homonyms.md)
- [model-states-module-ownership.md](./wiki/concepts/model-states-module-ownership.md)
- [module-filament-component-autoload-rule.md](./wiki/concepts/module-filament-component-autoload-rule.md)
- [module-root-uppercase-folders-archive.md](./wiki/concepts/module-root-uppercase-folders-archive.md)
- [no-app-support-queueable-actions.md](./wiki/concepts/no-app-support-queueable-actions.md)
- [no-services-no-support-queueable-actions.md](./wiki/concepts/no-services-no-support-queueable-actions.md)
- [organizzativa-money.md](./wiki/concepts/organizzativa-money.md)
- [phpstan-compliance.md](./wiki/concepts/phpstan-compliance.md)
- [phpstan-dynamic-array-normalization.md](./wiki/concepts/phpstan-dynamic-array-normalization.md)
- [ponytail-audit.md](./wiki/concepts/ponytail-audit.md)
- [second-brain-local-discipline.md](./wiki/concepts/second-brain-local-discipline.md)
- [testing.md](./wiki/concepts/testing.md)
- [ui-operating-model.md](./wiki/concepts/ui-operating-model.md)
- [ui-services-support-to-actions.md](./wiki/concepts/ui-services-support-to-actions.md)
- [xotbasefield-no-view-rule.md](./wiki/concepts/xotbasefield-no-view-rule.md)
## wiki/memories
- [lang-split-ui-claude-audit.md](./wiki/memories/lang-split-ui-claude-audit.md)
## wiki/overviews
- [ui-module.md](./wiki/overviews/ui-module.md)
## wiki/sources
- [ui-architecture-sources.md](./wiki/sources/ui-architecture-sources.md)
## wiki/troubleshooting
- [git-merge-conflict-inventory-1.md](./wiki/troubleshooting/git-merge-conflict-inventory-1.md)
- [git-merge-conflict-inventory-2026-04-28.deprecated.md](./wiki/troubleshooting/git-merge-conflict-inventory-2026-04-28.deprecated.md)
- [git-merge-conflict-inventory.md](./wiki/troubleshooting/git-merge-conflict-inventory.md)
