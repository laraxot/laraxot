<x-action-section>
    <x-slot name="title">
        {{ __('pub_theme::account_forms.delete_account.action.label') }}
    </x-slot>

    <x-slot name="description">
        {{ __('pub_theme::account_forms.delete_account.summary.help') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('pub_theme::account_forms.delete_account.warning.help') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('pub_theme::account_forms.delete_account.action.label') }}
            </x-danger-button>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('pub_theme::account_forms.delete_account.action.label') }}
            </x-slot>

            <x-slot name="content">
                {{ __('pub_theme::account_forms.delete_account.confirm_full.message') }}

                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-input type="password" class="mt-1 block w-3/4"
                             autocomplete="current-password"
                             placeholder="{{ __('pub_theme::account_forms.common.password.placeholder') }}"
                             x-ref="password"
                             wire:model="password"
                             wire:keydown.enter="deleteUser" />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                    {{ __('pub_theme::account_forms.common.cancel.label') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                    {{ __('pub_theme::account_forms.delete_account.action.label') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>