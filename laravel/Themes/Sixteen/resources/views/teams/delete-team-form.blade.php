<x-action-section>
<x-action-section>
    <x-slot name="title">
        {{ __('pub_theme::account_forms.team.delete.label') }}
    </x-slot>

    <x-slot name="description">
        {{ __('pub_theme::account_forms.team.delete_summary.help') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('pub_theme::account_forms.team.delete_warning.help') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                {{ __('pub_theme::account_forms.team.delete.label') }}
            </x-danger-button>
        </div>

        <!-- Delete Team Confirmation Modal -->
        <x-confirmation-modal wire:model.live="confirmingTeamDeletion">
            <x-slot name="title">
                {{ __('pub_theme::account_forms.team.delete.label') }}
            </x-slot>

            <x-slot name="content">
                {{ __('pub_theme::account_forms.team.delete_confirm.message') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                    {{ __('pub_theme::account_forms.common.cancel.label') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteTeam" wire:loading.attr="disabled">
                    {{ __('pub_theme::account_forms.team.delete.label') }}
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>
    </x-slot>
</x-action-section>
