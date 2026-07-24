<?php

declare(strict_types=1);

uses(\Modules\AiAssistant\Tests\TestCase::class);

use Modules\User\Models\User;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Modules\AiAssistant\Application\Speech\SpeechTranscriptionProviderInterface;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function (): void {
    $operatorRole = Role::findOrCreate('operator');
    $permission = Permission::findOrCreate('Use:AiAssistant');
    $operatorRole->givePermissionTo($permission);
});

it('transcribes audio via OpenAI and returns text', function (): void {
    app()->singleton(SpeechTranscriptionProviderInterface::class, function () {
        return new class implements SpeechTranscriptionProviderInterface {
            public function transcribe(UploadedFile $audio, string $language): string
            {
                return 'ciao mondo';
            }
        };
    });

    $user = User::factory()->create();
    $user->syncRoles(['operator']);

    actingAs($user);

    $audio = UploadedFile::fake()->create('audio.webm', 100, 'audio/webm');

    post('/aiassistant/stt', [
        'audio' => $audio,
        'lang' => 'it-IT',
    ])
        ->assertOk()
        ->assertJson([
            'ok' => true,
            'text' => 'ciao mondo',
            'language' => 'it',
        ]);
});

it('fails if OPENAI_API_KEY is missing', function (): void {
    app()->singleton(SpeechTranscriptionProviderInterface::class, function () {
        return new class implements SpeechTranscriptionProviderInterface {
            public function transcribe(UploadedFile $audio, string $language): string
            {
                throw new \RuntimeException('GROQ_API_KEY mancante in configurazione (config: services.groq.key).');
            }
        };
    });

    $user = User::factory()->create();
    $user->syncRoles(['operator']);

    actingAs($user);

    $audio = UploadedFile::fake()->create('audio.webm', 100, 'audio/webm');

    post('/aiassistant/stt', [
        'audio' => $audio,
    ])
        ->assertStatus(500)
        ->assertJson([
            'ok' => false,
        ]);
});

