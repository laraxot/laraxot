<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\WhatsApp\Models\WhatsAppContactIdentity;

/**
 * @extends Factory<WhatsAppContactIdentity>
 */
class WhatsAppContactIdentityFactory extends Factory
{
    /** @var class-string<WhatsAppContactIdentity> */
    protected $model = WhatsAppContactIdentity::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone_e164' => '+39'.$this->faker->unique()->numerify('3#########'),
            'wa_id' => $this->faker->numerify('###########'),
            'meta' => [],
        ];
    }
}
