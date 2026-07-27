<?php

declare(strict_types=1);

uses(TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Quotation\Actions\RecordSiteSurveyAction;
use Modules\Quotation\DTO\SiteSurveyAttachment;
use Modules\Quotation\Filament\Resources\SiteSurveyResource;
use Modules\Quotation\Filament\Resources\SiteSurveyResource\Concerns\RecordsSiteSurvey;
use Modules\Quotation\Models\SiteSurvey;
use Modules\Quotation\Tests\TestCase;
use Modules\User\Database\Seeders\PermissionsSeeder;
use Modules\User\Models\User;

use function Pest\Laravel\actingAs;

/**
 * Espone il trait dell'azione "Registra rilievo" senza montare la pagina
 * Filament: la mappatura form -> DTO e' la parte che puo' rompersi.
 */
final class SiteSurveyAttachmentMapperProbe
{
    use RecordsSiteSurvey;

    /** @return array<int, SiteSurveyAttachment> */
    public static function map(mixed $rows): array
    {
        return self::toAttachments($rows);
    }
}

function siteSurveyAdmin(): User
{
    app(PermissionsSeeder::class)->run();

    $user = User::factory()->create();
    $user->syncRoles(['admin']);

    return $user;
}

function makeSiteSurvey(): SiteSurvey
{
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Botti Termoidraulica',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var SiteSurvey $survey */
    $survey = SiteSurvey::query()->create([
        'code' => 'SL-0001',
        'customer_id' => $customerId,
        'title' => 'Rilievo caldaia',
        'status_code' => 'planned',
        'scheduled_at' => now(),
    ]);

    return $survey;
}

it('loads the site survey list, create and edit pages in the admin panel', function (): void {
    $user = siteSurveyAdmin();
    $survey = makeSiteSurvey();

    filament()->setCurrentPanel('admin');

    actingAs($user)->get(SiteSurveyResource::getUrl('index'))->assertStatus(200);
    actingAs($user)->get(SiteSurveyResource::getUrl('create'))->assertStatus(200);
    actingAs($user)->get(SiteSurveyResource::getUrl('edit', ['record' => $survey->id]))->assertStatus(200);
    actingAs($user)->get(SiteSurveyResource::getUrl('view', ['record' => $survey->id]))->assertStatus(200);
});

it('maps repeater rows to attachment DTOs and drops the ones without a file', function (): void {
    $attachments = SiteSurveyAttachmentMapperProbe::map([
        ['kind' => 'photo', 'title' => 'Caldaia', 'path' => 'site-surveys/foto.jpg'],
        ['kind' => 'audio', 'title' => '', 'path' => ['site-surveys/nota.m4a']],
        // Riga aggiunta e mai compilata: non deve diventare un allegato.
        ['kind' => 'video', 'title' => 'Vuoto', 'path' => ''],
        // Tipo non previsto: ricade su foto invece di far esplodere il DTO.
        ['kind' => 'hologram', 'path' => 'site-surveys/x.png'],
    ]);

    expect($attachments)->toHaveCount(3)
        ->and($attachments[0]->kind)->toBe('photo')
        ->and($attachments[0]->title)->toBe('Caldaia')
        ->and($attachments[1]->kind)->toBe('audio')
        ->and($attachments[1]->path)->toBe('site-surveys/nota.m4a')
        // Titolo vuoto non viene salvato come stringa vuota.
        ->and($attachments[1]->title)->toBeNull()
        ->and($attachments[2]->kind)->toBe('photo');
});

it('records a survey with its attachments through the resource action mapping', function (): void {
    $survey = makeSiteSurvey();

    $attachments = SiteSurveyAttachmentMapperProbe::map([
        ['kind' => 'photo', 'path' => 'site-surveys/1.jpg'],
        ['kind' => 'video', 'path' => 'site-surveys/2.mp4'],
        ['kind' => 'audio', 'path' => 'site-surveys/3.m4a'],
    ]);

    app(RecordSiteSurveyAction::class)->execute(
        survey: $survey,
        attachments: $attachments,
        notes: 'Tubazione da rifare',
    );

    $survey->refresh();

    expect($survey->status_code)->toBe('done')
        ->and($survey->notes)->toBe('Tubazione da rifare')
        ->and($survey->performed_at)->not->toBeNull()
        ->and($survey->photos()->count())->toBe(1)
        ->and($survey->videos()->count())->toBe(1)
        ->and($survey->voiceNotes()->count())->toBe(1);
});
