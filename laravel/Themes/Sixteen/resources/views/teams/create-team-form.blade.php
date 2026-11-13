<x-form-section submit="createTeam">
<x-form-section submit="createTeam">
    <x-slot name="title">
        {{ __('pub_theme::account_forms.team.details.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('pub_theme::account_forms.team.create.help') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-label value="{{ __('pub_theme::account_forms.team.owner.label') }}" />
            <x-label value="{{ __('pub_theme::account_forms.team.owner.label') }}" />

            <div class="flex items-center mt-2">
                <img class="size-12 rounded-full object-cover" src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}">

                <div class="ms-4 leading-tight">
                    <div class="text-gray-900 dark:text-white">{{ $this->user->name }}</div>
                    <div class="text-gray-700 dark:text-gray-300 text-sm">{{ $this->user->email }}</div>
                </div>
            </div>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('pub_theme::account_forms.team.name.label') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" autofocus />
            <x-input-error for="name" class="mt-2" />
            <x-label for="name" value="{{ __('pub_theme::account_forms.team.name.label') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" autofocus />
            <x-input-error for="name" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-button>
            {{ __('pub_theme::account_forms.common.create.label') }}
        </x-button>
    </x-slot>
</x-form-section>
