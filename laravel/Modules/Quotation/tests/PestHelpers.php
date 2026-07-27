<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Modules\Quotation\Filament\Pages\RecordQuotationVoicePage;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

/**
 * @param  array<string, mixed>  $attributes
 */
function quotationOperator(array $attributes = []): User
{
    $role = Role::findOrCreate('operator');
    $user = UserFactory::new()->createOne($attributes);
    $user->syncRoles([$role]);

    return $user;
}

/**
 * @return Testable<RecordQuotationVoicePage>
 */
function testQuotationVoicePage(): Testable
{
    return Livewire::test(RecordQuotationVoicePage::class);
}

/**
 * @param  array<string, string>  $query
 * @return Testable<RecordQuotationVoicePage>
 */
function testQuotationVoicePageWithQuery(array $query): Testable
{
    Livewire::withQueryParams($query);

    return Livewire::test(RecordQuotationVoicePage::class);
}

/**
 * @return array<string, mixed>
 */
function httpClientRequestData(Request $request): array
{
    $data = $request->data();

    if (! is_array($data)) {
        return [];
    }

    /** @var array<string, mixed> $normalized */
    $normalized = $data;

    return $normalized;
}

/**
 * @param  array<mixed, mixed>  $messages
 */
function httpMessagesPrompt(array $messages): string
{
    return implode("\n", array_map(
        static function (mixed $message): string {
            if (! is_array($message)) {
                return '';
            }

            return (string) ($message['content'] ?? '');
        },
        $messages,
    ));
}
