{{--
    Footer links block - usa i dati del blocco (product, company, legal).
    Le URL sono già localizzate da ResolveLocalizedBlockDataAction.
--}}
@php
    $product = $product ?? [];
    $company = $company ?? [];
    $legal = $legal ?? [];
    $locale = app()->getLocale();
    $getLabel = fn ($item) => is_array($item) ? ($item[$locale] ?? $item['it'] ?? $item['en'] ?? '') : (string) $item;
@endphp

<div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
    @foreach (['product' => $product, 'company' => $company, 'legal' => $legal] as $groupKey => $group)
        @if (!empty($group['links']))
            <div>
                <h4 class="mb-2 text-sm font-semibold text-white">
                    {{ $getLabel($group['title'] ?? $groupKey) }}
                </h4>
                <ul class="space-y-2">
                    @foreach ($group['links'] as $link)
                        <li>
                            <a href="{{ $link['url'] ?? '#' }}"
                               class="text-sm text-gray-300 hover:text-white transition-colors">
                                {{ $getLabel($link['label'] ?? '') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endforeach
</div>
