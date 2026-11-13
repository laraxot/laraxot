{{--
    Riepilogo finale (passo 5, `layout = centered`): colonna centrale con i riquadri di ogni passo e i pulsanti.
    JSON: summary.hidden_title, summary.title, summary.edit, summary.groups[title, rows[label, value]].
--}}
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="mt-2">
                <h2 class="visually-hidden">{{ $d('summary.hidden_title') }}</h2>
                <div class="cmp-card mb-4">
                    <div class="card has-bkg-grey shadow-sm mb-0">
                        <div class="card-header border-0 p-0 mb-lg-20">
                            <div class="d-flex"><h3 class="subtitle-large mb-0">{{ $d('summary.title') }}</h3></div>
                        </div>
                        <div class="card-body p-0">
                            @foreach ($d('summary.groups', []) as $group)
                                @include('pub_theme::components.blocks.appointment-step.partials.info-summary', [
                                    'class' => $loop->last ? 'bg-white p-3 p-lg-4 mb-0' : 'bg-white mb-3 mb-lg-4 p-3 p-lg-4',
                                    'title' => $group['title'] ?? '',
                                    'rows' => $group['rows'] ?? [],
                                    'edit' => $d('summary.edit'),
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @include('pub_theme::components.blocks.appointment-step.partials.nav-steps')
        </div>
    </div>
</div>
