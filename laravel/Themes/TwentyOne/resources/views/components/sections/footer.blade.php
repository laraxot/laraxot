<footer class="py-8 text-white bg-gray-900 xl:py-16">
    <div class="container max-w-6xl mx-auto space-y-6">
        @php
            $footerLinks = array_values(array_filter(
                $blocks,
                static fn ($block): bool => str_contains((string) $block->view, 'footer_links')
            ));
            $footerLinksBlock = $footerLinks[0] ?? null;
        @endphp

        <div class="space-y-4">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-white">{{ config('app.name') }}</p>
                    <p class="max-w-xl text-sm text-gray-400">
                        Prediction market con crediti virtuali a tappi di bottiglia, mercati chiari e regole trasparenti.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="grid text-sm font-semibold text-gray-900 bg-white rounded place-items-center size-8">
                        <span>18+</span>
                    </div>
                </div>
            </div>

            @if ($footerLinksBlock !== null)
                <div class="flex flex-col gap-6 border-t border-white/10 pt-4 md:flex-row md:items-start md:justify-between">
                    @include($footerLinksBlock->view, $footerLinksBlock->data)
                </div>
            @endif
        </div>
    </div>
</footer>
