<div>
	<button class="flex items-center space-x-1 text-sm font-semibold text-gray-600 hover:text-blue-600" data-dropdown-toggle="dropdown-markets">
		<x-heroicon-o-globe-europe-africa class="size-6"/>
		<span>{{ __('pub_theme::headernav.markets') }}</span>
		<x-heroicon-o-chevron-down class="size-4"/>
	</button>
	<div id="dropdown-markets" class="absolute z-20 hidden p-2 overflow-hidden text-sm bg-white border border-white rounded-lg">
		<ul class="flex flex-wrap max-w-4xl gap-1">
			@foreach($_theme->categories() as $category)
			{{-- {{ dddx($category) }} --}}
			<li class="flex flex-col w-[220px] p-3 space-y-4 transition-colors rounded justify-content-between hover:ring-1 hover:ring-gray-100">
				<a href="
					{{-- {{ "/categories/{$category->slug}" }} --}}
					{{ route('category.view', ['lang'=>$lang,'slug' => $category->slug ]) }}
					">
					<div class="flex items-center space-x-2">
						<span class="grid text-blue-600 bg-blue-100 rounded place-items-center size-8">
							@if($category->icon == null)
								<x-heroicon-o-question-mark-circle class="size-6"/>
							@else
								@svg($category->icon, 'size-6')
							@endif
						</span>
						<span class="font-semibold">{{ $category->title }}</span>
					</div>
				</a>
				<ul class="space-y-1 grow">
					@foreach($category->children as $child)
					<li>
						<a class="block p-1 -ms-1 hover:text-blue-600" 
							href="
								{{-- {{ "/categories/{$child->slug}" }} --}}
								{{ route('category.view', ['lang'=>$lang,'slug' => $child->slug ]) }}
								">
							{{ $child->title }}
						</a>
					</li>
					@endforeach
				</ul>
				{{-- <div>
					<a class="py-2 text-xs font-semibold text-blue-500 hover:text-blue-600" 
						href="{{ "/categories/{$category->slug}" }}">View more</a>
				</div> --}}
			</li>
			@endforeach
		</ul>
	</div>
</div>