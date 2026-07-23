<section class="space-y-12">
    <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
        <div class="relative grid w-20 h-20 bg-gray-200 rounded-full place-items-center overflow-clip">
            {{-- <x-heroicon-o-user class="w-8 h-8 text-gray-400"/> --}}
            <img class="absolute object-cover w-full h-full text-gray-400" src="{{ $_profile?->getAvatarUrl() ?? 'https://placehold.co/200x200' }}" alt="">
        </div>
		<div class="space-y-2 text-center grow sm:text-left">
			<h2 class="text-2xl">{{ $_profile->user->name }}</h2>
            {{-- <div class="flex space-x-4">
                <div>0 <small class="text-sm text-gray-500">Followers</small></div>
                <div>0 <small class="text-sm text-gray-500">Following</small></div>
            </div> --}}
		</div>
        <div class="flex flex-wrap gap-2">
            <button wire:click="editEmail" class="flex items-center px-3 py-2 space-x-2 text-sm font-semibold transition rounded-lg text-nowrap ring-1 ring-gray-200 bg-gray-50 ring-inset hover:bg-gray-100 hover:no-underline">
                <x-heroicon-o-pencil class="size-4"/>
                <x-filament::loading-indicator class="w-5 h-5" wire:loading wire:target="editEmail"/>
                <span>Email</span>
            </button>
            <button wire:click="editPassword" class="flex items-center px-3 py-2 space-x-2 text-sm font-semibold transition rounded-lg text-nowrap ring-1 ring-gray-200 bg-gray-50 ring-inset hover:bg-gray-100 hover:no-underline">
                <x-heroicon-o-pencil class="size-4"/>
                <x-filament::loading-indicator class="w-5 h-5" wire:loading wire:target="editPassword"/>
                <span>Password</span>
            </button>
            <button wire:click="editProfile" class="flex items-center px-3 py-2 space-x-2 text-sm font-semibold transition rounded-lg text-nowrap ring-1 ring-gray-200 bg-gray-50 ring-inset hover:bg-gray-100 hover:no-underline">
                <x-heroicon-o-pencil class="size-4"/>
                <x-filament::loading-indicator class="w-5 h-5" wire:loading wire:target="editProfile"/>
                <span>Edit</span>
            </button>
            {{-- <a href="#" class="flex items-center px-3 py-2 space-x-2 text-sm font-semibold transition rounded-lg text-nowrap ring-1 ring-gray-200 bg-gray-50 ring-inset hover:bg-gray-100 hover:no-underline">
                <x-heroicon-o-power class="text-red-500 size-4"/>
                <span>Logout</span>
            </a> --}}
        </div>
	</div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <div class="flex items-center p-4 space-x-4 border rounded-xl">
            <x-heroicon-o-arrows-up-down class="text-blue-500 size-8"/>
            <div>
                <div class="text-gray-500">Market traded</div>
                <h2 class="text-4xl font-bold">
                    {{ $_profile->ratingMorphs->count() }}
                </h2>
            </div>
        </div>
        <div class="flex items-center p-4 space-x-4 border rounded-xl">
            <x-heroicon-o-arrows-up-down class="text-blue-500 size-8"/>
            <div>
                <div class="text-gray-500">Total outcome</div>
                <h2 class="text-4xl font-bold">
                    {{ $_profile->ratingMorphs->sum('value') }} <span class="text-3xl font-medium text-gray-400">ø</span>
                </h2>
            </div>
        </div>
        <div class="flex items-center p-4 space-x-4 border rounded-xl">
            <x-heroicon-o-receipt-percent class="text-blue-500 size-8"/>
            <div>
                <div class="text-gray-500">Win Rate</div>
                @if($_profile->ratingMorphs->count() > 0)
                    <h2 class="text-4xl font-bold">
                        {{ number_format($_profile->ratingMorphs->where('is_winner')->count() / $_profile->ratingMorphs->count() * 100, 2) }}%
                    </h2>
                @else
                    <h2 class="text-4xl font-bold">
                        0%
                    </h2>
                @endif
            </div>
        </div>
    </div>


    @php
        $_profile_transanctions = $_profile->transanctions;
    @endphp
    <div>
        <h6 class="mb-2 text-xs text-gray-400">TRANSACTIONS <span class="text-blue-400">({{ $_profile_transanctions->count()/** @phpstan-ignore method.nonObject */}})</span></h6>
        <div class="py-2 overflow-x-auto">
            <table class="w-full" cellpadding="12">
                <thead>
                    <tr class="text-sm text-gray-400 bg-gray-50">
                        <th class="text-start">{{ (string) __('blog::profile.setting.date') }}</th>
                        <th>{{ (string) __('blog::profile.setting.action') }}</th>
                        <th class="text-start">{{ (string) __('blog::profile.setting.market') }}</th>
                        <th>{{ (string) __('blog::profile.setting.outcome') }}</th>
                        <th>{{ (string) __('blog::profile.setting.option') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($_profile->transanctions as $trans)
                        <tr>
                            <td>{{ $trans->created_at }}</td>
                            <td class="text-center">{{ (string) __('blog::profile.setting.'.$trans->note) ?? 'not defined' }}</td>
                            <td>
                                @if($trans->model_type == 'profile')
                                    -
                                @else
                                    @php
                                        $rating_morph = $trans->getRatingMorph();
                                    @endphp
                                    <a href="{{ route('article.view', ['lang'=>$lang,'slug' => $rating_morph->model->slug ]) }}" target="_blank" class="text-blue-500">
                                        {{ $rating_morph->model->title }}
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">{{ $trans->credits }}</td>
                            <td class="text-center">
                                @if($trans->model_type == 'profile')
                                    -
                                @else
                                    @if($rating_morph->rating != null)
                                        {{ $rating_morph->rating->title }}
                                    @else
                                        {{ (string) __('predict::bet.not-defined') }}
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <x-filament-actions::modals />

</section>