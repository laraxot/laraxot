<div
	class="bottom-0 left-0 z-40 flex-none w-full lg:w-1/3 max-md:fixed lg:sticky lg:top-20 lg:p-4 lg:z-[5]"
	{{-- x-data="{isloggedIn:true}" --}}
	>

	@if(Auth::guest())
		@include('predict::livewire.article.ratings.for-image.v1.guest')
	@else
		@if (isset($article->closed_at) && $article->closed_at->lte(now()))
			<div class="p-6 space-y-2 text-center rounded text-rose-600 ring-1 bg-rose-400/5 ring-rose-400/10">
				<h4 class="text-xl font-semibold">Uh No!</h4>
				<p class="text-sm">
					{{ __('blog::article.no_vote', ['time' => $article->closed_at->diffForHumans()]) }}
					{{-- 
					Sorry, but this vote is closed 
					{{ $article->closed_at->diffForHumans() }}, 
					please try to find another vote 
					<a class="font-semibold" href="/">
						here
					</a> --}}
				</p>
			</div>
		@else
			{{-- @include('blog::livewire.article.ratings.for-image.v1.check') --}}
			<livewire:article.ratings.for-image :article="$article" wire:key="{{ $article->id }}" type="show"/>
		@endif
	@endif


	{{-- @if ($article->sidebar_blocks)
		<x-render.blocks :blocks="$article->sidebar_blocks" :model="$article" />
	@endif --}}


{{--
	@if(Auth::guest())
		@include('pub_theme::article.show.sidebar.guest')
	@else
		@include('pub_theme::article.show.sidebar.check')
	@endif --}}
</div>
