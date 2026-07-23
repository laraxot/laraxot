{{--
/**
 * Header section v1 - Data-driven Nav bar.
 * Follows WCAG 2.1 AA standards.
 * Inspired by Meetup theme implementation.
 */
--}}
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200" role="banner">
    @php
        $pos = collect($blocks)->groupBy('data.position');
    @endphp
    <nav class="px-6 py-4 mx-auto container-xl" role="navigation" aria-label="{{ __('pub_theme::headernav.main') }}">
        <ul class="flex items-center justify-between lg:grid lg:grid-cols-3 gap-x-4">
            {{-- Left Position --}}
            <li class="flex items-center space-x-8">
                @foreach($pos->get('left', []) as $block)
                    @php
                        try {
                            echo view($block->view, array_merge($block->data, ['blocks' => $blocks]))->render();
                        } catch (\Throwable $e) {
                            // Skip blocks with missing dependencies
                        }
                    @endphp
                @endforeach
            </li>

            {{-- Center Position (Desktop Only) --}}
            <li class="hidden grow md:block">
                @foreach($pos->get('center', []) as $block)
                    @php
                        try {
                            echo view($block->view, array_merge($block->data, ['blocks' => $blocks]))->render();
                        } catch (\Throwable $e) {
                            // Skip blocks with missing dependencies
                        }
                    @endphp
                @endforeach
            </li>

            {{-- Right Position --}}
            <li class="flex items-center justify-end gap-x-4">
                @foreach($pos->get('right', []) as $block)
                    @php
                        try {
                            echo view($block->view, array_merge($block->data, ['blocks' => $blocks]))->render();
                        } catch (\Throwable $e) {
                            // Skip blocks with missing dependencies
                        }
                    @endphp
                @endforeach
            </li>
        </ul>
    </nav>
</header>
