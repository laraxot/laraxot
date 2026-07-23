@props([
    'vm' => null,
    'ns' => 'fixcity::ticket',
    'includeCtaInPanels' => false,
])

@if ($vm !== null)
    <div class="col-lg-8 offset-lg-1">
        @include('pub_theme::components.blocks.ticket.results-header', [
            'ns' => $ns,
            'resultsCount' => $vm->resultsCount(),
            'sprite' => $vm->sprite(),
        ])

        <div class="tab-section">
            @include('pub_theme::components.blocks.ticket.tabs', [
                'tabs' => $vm->tabs(),
                'sectionId' => $vm->tabsId(),
            ])

            <div class="tab-content">
                @php $cta = $includeCtaInPanels ? $vm->cta() : []; @endphp
                <div
                    id="{{ $vm->defaultPanelId() }}"
                    role="tabpanel"
                    class="tab-pane fade show active"
                >
                    <div class="row">
                        <div class="col-12">
                    <div class="map-box">
                    @if ($vm->useReferenceStaticMap())
                        <img
                            src="{{ $vm->referenceMapImageUrl() }}"
                            alt="{{ __($ns . '.map.image.alt') }}"
                            class="w-100"
                            width="1200"
                            height="661"
                        >
                    @else
                        <map-lit
                            id="block-map"
                            class="w-100"
                            legend-mode="sidebar"
                            data-url="{{ $vm->mapDataUrl() }}"
                            height="clamp(305px,52vw,661px)"
                            style="height:clamp(305px,52vw,661px);display:block;width:100%"
                            aria-label="{{ __($ns . '.map.image.alt') }}"
                        ></map-lit>
                    @endif
                    </div>
                        </div>
                         @if (!empty($cta))
                             <div class="col-lg-6 mt-50 mb-4 mb-lg-0">
                                 @include('pub_theme::components.blocks.cta.ticket', [
                                     'cta' => $cta
                                 ])
                             </div>
                         @endif
                    </div>
                </div>

<div id="data-ex-disservizio2" role="tabpanel" class="tab-pane fade">
                     <div class="row">
                         @forelse ($vm->liveTickets() as $item)
                             @include('pub_theme::components.blocks.ticket.ticket-card', [
                                 'item' => $item,
                                 'loop' => $loop,
                                 'ns' => $ns,
                                 'sprite' => $vm->sprite(),
                             ])
                         @empty
                             <div class="col-12 text-center py-5">
                                 <p class="subtitle-small text-muted">{{ __($ns . '.results.empty') }}</p>
                             </div>
                         @endforelse
                     </div>
                     <button type="button" class="btn btn-outline-primary mobile-full py-3 mt-10 mx-auto">
                         <span>{{ __($ns . '.load-more.button.label') }}</span>
                     </button>
                     @if (!empty($cta))
                     <div class="row mt-50 mb-4 mb-lg-0">
                         <div class="col-lg-6">
                             @include('pub_theme::components.blocks.cta.ticket', [
                                 'cta' => $cta,
                             ])
                         </div>
                     </div>
                     @endif
                 </div>
            </div>
        </div>
    </div>
@endif
