@props(['data' => []])

@php
    use Modules\Fixcity\Models\Ticket;

    $ns = 'fixcity::ticket';
    $blockData = is_array($data) ? $data : [];

    $t = function ($value, $default = '') {
        if (empty($value)) {
            return $default;
        }

        return str_contains((string) $value, '::') ? __((string) $value) : (string) $value;
    };

    $breadcrumbItems = [];
    foreach ($blockData['breadcrumb'] ?? [] as $item) {
        $breadcrumbItems[] = [
            'label' => $t($item['label'] ?? ''),
            'url' => $item['url'] ?? null,
            'active' => $item['active'] ?? false,
        ];
    }

    $title = $t($blockData['title'] ?? '', __($ns . '.heading.title.label'));

    $resolvedCount = (int) ($blockData['resolved_count'] ?? Ticket::query()
        ->where('status', 'resolved')
        ->where('updated_at', '>=', now()->subYear())
        ->count());
    $subtitleRaw = $blockData['subtitle'] ?? '';
    $subtitleKey = (! empty($subtitleRaw) && str_contains((string) $subtitleRaw, '::'))
        ? (string) $subtitleRaw
        : $ns . '.heading.subtitle.text';
    $subtitle = trans_choice($subtitleKey, $resolvedCount, ['count' => $resolvedCount]);
@endphp

<div class="col-12 col-lg-10">
    <div class="cmp-breadcrumbs" role="navigation">
        <nav class="breadcrumb-container" aria-label="breadcrumb">
            <ol class="breadcrumb p-0" data-element="breadcrumb">
                @foreach ($breadcrumbItems as $item)
                    <li class="breadcrumb-item{{ $item['active'] ?? false ? ' active' : '' }}"{{ $item['active'] ?? false ? ' aria-current="page"' : '' }}>
                        @if ($item['url'] ?? false)
                            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a><span class="separator">/</span>
                        @else
                            {{ $item['label'] }}
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>
    <div class="cmp-heading p-0">
        <h1 class="title-xxxlarge">{{ $title }}</h1>
        @if ($subtitle)
            <p class="subtitle-small">{{ $subtitle }}</p>
        @endif
    </div>
</div>
