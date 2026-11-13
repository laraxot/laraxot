@props(['data' => []])
{{--
    Breadcrumb Design Comuni (`#main-container`): `breadcrumb.items[]` (label, url) e `breadcrumb.current`. Usato dai blocchi
    list-page, service-page-list e service-page-detail (stesse classi del riferimento).
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
@endphp
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-breadcrumbs" role="navigation">
                <nav class="breadcrumb-container" aria-label="{{ __('pub_theme::components_common.a11y.breadcrumb_trail.label') }}"><ol class="breadcrumb p-0" data-element="breadcrumb">
                    @foreach ($d('breadcrumb.items', []) as $crumb)
                        <li class="breadcrumb-item"><a href="{{ $crumb['url'] ?? '#' }}">{{ $crumb['label'] ?? '' }}</a><span class="separator">/</span></li>
                    @endforeach
                    <li class="breadcrumb-item active" aria-current="page">{{ $d('breadcrumb.current') }}</li>
                </ol></nav>
            </div>
        </div>
    </div>
</div>
