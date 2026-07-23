<header class="sticky top-0 z-50 bg-white">
	@php
	$pos=collect($blocks)->groupBy('data.position');
	
	@endphp
	<nav class="px-6 py-4 mx-auto container-xl">
		<ul class="flex items-center justify-between lg:grid lg:grid-cols-3 gap-x-4">
			<li class="flex items-center space-x-8">
				@foreach($pos->get('left') as $block)
				@php
					try {
					    echo view($block->view, $block->data)->render();
					} catch (\Throwable $e) {
					    // Skip blocks whose optional datasource is not available yet.
					}
				@endphp
				@endforeach
			</li>
			<li class="hidden grow md:block">
				@foreach($pos->get('center') as $block)
				@php
					try {
					    echo view($block->view, $block->data)->render();
					} catch (\Throwable $e) {
					    // Skip blocks whose optional datasource is not available yet.
					}
				@endphp
				@endforeach
			</li>
			<li class="flex items-center justify-end gap-x-4">
				{{-- {{ \Filament\Support\Facades\FilamentView::renderHook(\Modules\Predict\Enums\Front\TopBarEnum::USER_MENU_BEFORE->value) }}   --}}
				@foreach($pos->get('right') as $block)
				@php
					try {
					    echo view($block->view, $block->data)->render();
					} catch (\Throwable $e) {
					    // Skip blocks whose optional datasource is not available yet.
					}
				@endphp
				@endforeach
			</li>
		</ul>
	</nav>
</header>
