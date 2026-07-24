<?php

declare(strict_types=1);

namespace Modules\Signature\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Signature\Models\Signature;

/**
 * @extends Factory<Signature>
 */
class SignatureFactory extends Factory
{
    /** @var class-string<Signature> */
    protected $model = Signature::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'signable_type' => 'Modules\Intervention\Models\Intervention',
            'signable_id' => 1,
            'user_id' => 1,
            'disk' => 'public',
            'path' => null,
            'mime_type' => 'image/png',
            'signed_at' => now(),
            'payload' => [],
        ];
    }
}
