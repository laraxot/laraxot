@props(['data' => []])

{{-- 
    Slim Footer Template
    Usage: <x-section slug="footer" tpl="slim" />
--}}

<footer class="it-footer it-footer-slim" id="footer">
    <div class="it-footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12 footer-items-wrapper">
                    <div class="flex justify-between items-center flex-wrap gap-4">
                        {{-- Logo --}}
                        <div class="it-brand-wrapper">
                            <a href="{{ $data['home_url'] ?? '/it' }}">
                                <svg class="icon" aria-hidden="true">
                                    <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-pa') }}"></use>
                                </svg>
                                <div class="it-brand-text">
                                    <h2 class="no_toc text-white text-lg">{{ $data['site_name'] ?? 'Nome del Comune' }}</h2>
                                </div>
                            </a>
                        </div>
                        
                        {{-- Bottom Links --}}
                        <ul class="footer-bottom-list list-inline flex gap-4 flex-wrap">
                            @foreach($data['bottom_links'] ?? [
                                ['label' => 'Media policy', 'url' => '#'],
                                ['label' => 'Note legali', 'url' => '#'],
                                ['label' => 'Privacy policy', 'url' => '#'],
                                ['label' => 'Mappa del sito', 'url' => '#'],
                            ] as $link)
                            <li class="list-inline-item">
                                <a href="{{ $link['url'] }}" class="text-underline text-white/60 hover:text-white transition-colors">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
