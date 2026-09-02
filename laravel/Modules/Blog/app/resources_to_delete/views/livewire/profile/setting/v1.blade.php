@php
// dddx(get_defined_vars());
$currentTab = request()->query('tab', 'profile');

if(!in_array($currentTab, ['profile', 'settings'])){
	abort(404);
}

@endphp

<div class="p-6 space-y-8 bg-white lg:space-y-12 lg:p-10">
	<ul class="flex items-center">
		<li>
			<a 
				href="{{ url("/$lang/pages/setting") }}" 
				class="flex items-center px-3 py-2 transition-colors space-x-1 border-b-2 {{ $currentTab == 'profile' ? 'border-blue-500' : 'border-gray-200'}}">
				<x-heroicon-o-user class="size-5"/>
				<span>Profile</span>
			</a>
		</li>
		<li>
			<a 
				href="{{ url("/$lang/pages/setting?tab=settings") }}" 
				{{-- href="{{ route('page_slug.show', ['lang'=>$lang,'page_slug' => $page->slug ]) }}" --}}
				class="flex items-center px-3 py-2 transition-colors space-x-1 border-b-2 {{ $currentTab == 'settings' ? 'border-blue-500' : 'border-gray-200'}}">
				<x-heroicon-o-cog-6-tooth class="size-5"/>
				<span>Setting</span>
			</a>
		</li>
	</ul>
	<div>
		@include("blog::livewire.profile.setting.components.{$currentTab}")
	</div>
</div>