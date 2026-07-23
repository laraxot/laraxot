<div
	x-data="{loggedIn:true}"
	class="max-w-[calc(100%-30px)] sm:max-w-[calc(100%-80px)] lg:max-w-[996px] mx-auto pb-12 font-roboto"
	>
	<div class="py-10">
		<h1 class="text-[2rem] mb-4 font-roboto font-semibold text-neutral-5">
			{{ $page->title }}
		</h1>
	</div>

	
	@if(!empty($page->sidebar_blocks))
		<div class="grid grid-cols-1 lg:grid-cols-[21.25rem,1fr] gap-4">
			<div class="space-y-6">
				<x-page side="sidebar" :slug="$page->slug" />
			</div>

			<x-page side="content" :slug="$page->slug" />
		</div>
	
	@else
		{{-- what is the css to make the whole block unsplit? --}}
		<div>
			<x-page side="content" :slug="$page->slug" />
		</div>
	@endif


</div>