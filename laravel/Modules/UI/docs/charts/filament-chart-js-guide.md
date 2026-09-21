---
title: "Filament Chart.js Guide"
type: guide
tags: [filament, chart, guide]
created: 2026-07-14
updated: 2026-07-14
qmd: "filament-chart-js-guide filament chart.js guide"
<<<<<<< HEAD
issues: ["https://github.com/provtv/<nome repository>/issues/124"]
discussions: ["https://github.com/provtv/<nome repository>/discussions/1"]
=======
<<<<<<< HEAD
issues: ["https://github.com/provtv/<nome repository>/issues/124"]
discussions: ["https://github.com/provtv/<nome repository>/discussions/1"]
=======
<<<<<<< HEAD
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
=======
issues: ["https://github.com/provtv/<nome repository>/issues/124"]
discussions: ["https://github.com/provtv/<nome repository>/discussions/1"]
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
related:
  - "./chartjs-datalabels-multiple-labels-complete-guide.md"
  - "./chartjs-plugin-datalabels-filament5.md"
  - "./export-strategy.md"
  - "./server-side-actions.md"
  - "./shared-hosting-strategy.md"
---
# Filament Chart.js Guide

> **Why this guide?**: To standardize how we use Chart.js in Filament, especially regarding advanced features like plugins (Zoom, Annotations) which are not enabled by default.

## 1. Basic Usage (Filament Standard)

Filament wraps Chart.js. Always use `XotBaseWidget` if available, or standard Filament `ChartWidget`.

```php
use Filament\Widgets\ChartWidget;

class BlogPostsChart extends ChartWidget
{
    protected static ?string $heading = 'Blog Posts';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
```

## 2. Advanced: Using Plugins (Zoom, Annotation)

Filament doesn't bundler all Chart.js plugins. To use them, you must register them via a custom JavaScript file.

### Step 1: Install Plugins

```bash
npm install chartjs-plugin-zoom chartjs-plugin-annotation
```

### Step 2: Register in `resources/js/app.js`

You need to import and register the plugins globally or specifically.

```javascript
import Chart from 'chart.js/auto';
import zoomPlugin from 'chartjs-plugin-zoom';
import annotationPlugin from 'chartjs-plugin-annotation';

Chart.register(zoomPlugin, annotationPlugin);
```

### Step 3: Configure in PHP Widget

Pass the options in the `getOptions()` method.

```php
protected function getOptions(): array
{
    return [
        'plugins' => [
            'zoom' => [
                'zoom' => [
                    'wheel' => ['enabled' => true],
                    'pinch' => ['enabled' => true],
                    'mode' => 'xy',
                ],
                'pan' => [
                    'enabled' => true,
                    'mode' => 'xy',
                ],
            ],
            'annotation' => [
                'annotations' => [
                    'line1' => [
                        'type' => 'line',
                        'yMin' => 60,
                        'yMax' => 60,
                        'borderColor' => 'rgb(255, 99, 132)',
                        'borderWidth' => 2,
                    ],
                ],
            ],
        ],
    ];
}
```

## 3. Best Practices

-   **Data Loading**: Use `polling` sparingly to avoid server load.

## 4. Professional Configuration (Standards 2026)

To achieve a premium "SaaS" look, configure your `getOptions()` to control fonts, layouts, and tooltips.
See the **[LimeSurvey Professional Charts Guide](../../../limesurvey/docs/professional-charts-and-pdfs.md)** for the detailed specification on:
-   Font consistency (Inter/Roboto).
-   Legend positioning.
-   Gridline reduction (Data-Ink Ratio).

## 5. PDF Reporting Strategy

**Do NOT** use `dompdf` or client-side canvas capture for charts.
<<<<<<< HEAD
The architectural standard for modulo questionari is **Spatie Laravel PDF** (a wrapper around Browsershot).
=======
<<<<<<< HEAD
The architectural standard for modulo questionari is **Spatie Laravel PDF** (a wrapper around Browsershot).
=======
<<<<<<< HEAD
The architectural standard for Quaeris is **Spatie Laravel PDF** (a wrapper around Browsershot).
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
The architectural standard for Quaeris is **Spatie Laravel PDF** (a wrapper around Browsershot).
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
The architectural standard for modulo questionari is **Spatie Laravel PDF** (a wrapper around Browsershot).
=======
The architectural standard for Quaeris is **Spatie Laravel PDF** (a wrapper around Browsershot).
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

**Pattern:** "Shadow Report Views"
1.  Create a dedicated Blade view for the report (linear layout).
2.  Inject the *exact same* data aggregations used by your Dashboard widgets.
3.  Use `Pdf::view(...)` to render.
4.  **Critical**: Set `animation: false` in Chart.js options for the print view.

---
**See Also**:
-   [Dashboard Best Practices](../../../limesurvey/docs/dashboard-best-practices.md)
-   [Professional Charts & PDF Guide](../../../limesurvey/docs/professional-charts-and-pdfs.md)
