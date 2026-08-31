<?php

declare(strict_types=1);

namespace Modules\Rating\Tests\Feature;

use Modules\Rating\Models\Rating;
use Modules\Rating\Tests\TestCase;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(\Modules\Rating\Tests\TestCase::class);

it('can list ratings', function (): void {
    Rating::create([
        'name' => 'Test Rating 1',
    ]);

    Rating::create([
        'name' => 'Test Rating 2',
    ]);

    $response = getJson('/api/ratings');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

it('can create rating', function (): void {
    $data = [
        'name' => 'New Rating',
        'color' => '#00FF00',
    ];

    $response = postJson('/api/ratings', $data);

    $response->assertStatus(201)
        ->assertJson([
            'data' => [
                'name' => 'New Rating',
            ],
        ]);
});

it('can update rating', function (): void {
    $rating = Rating::create([
        'name' => 'Test Rating',
    ]);

    $data = [
        'name' => 'Updated Rating',
    ];

    $response = putJson("/api/ratings/{$rating->id}", $data);

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'Updated Rating',
            ],
        ]);
});

it('can delete rating', function (): void {
    $rating = Rating::create([
        'name' => 'Test Rating',
    ]);

    $response = deleteJson("/api/ratings/{$rating->id}");

    $response->assertStatus(204);
    expect(Rating::find($rating->id))->toBeNull();
});

it('can rate model', function (): void {
    $rating = Rating::create([
        'name' => 'Test Rating',
    ]);

    $data = [
        'model_type' => 'test_model',
        'model_id' => 1,
        'value' => 4.5,
        'note' => 'Great!',
    ];

    $response = postJson("/api/ratings/{$rating->id}/rate", $data);

    $response->assertStatus(201)
        ->assertJson([
            'data' => [
                'rating_id' => $rating->id,
                'value' => 4.5,
                'note' => 'Great!',
            ],
        ]);
});
