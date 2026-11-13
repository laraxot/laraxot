{{-- Guscio del passo: briciole di pane, h1 e barra dei passi (stessa struttura di report-step/layout-summary). Dati: $d('title'), 'breadcrumb', 'steps'. --}}
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        @include($parts.'.breadcrumb')
        <div class="cmp-heading pb-3 pb-lg-4"><h1 class="title-xxxlarge">{{ $d('title') }}</h1></div>
    </div>
    <div class="col-12">
        @include($parts.'.stepper')
    </div>
</div>
