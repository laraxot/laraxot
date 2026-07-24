<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\WhatsApp\Models\WhatsAppConversation;

/**
 * @extends Factory<WhatsAppConversation>
 */
class WhatsAppConversationFactory extends Factory
{
    /** @var class-string<WhatsAppConversation> */
    protected $model = WhatsAppConversation::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone_e164' => '+39'.$this->faker->unique()->numerify('3#########'),
            'wa_id' => $this->faker->numerify('###########'),
            'display_name' => $this->faker->name(),
            'status' => 'open',
            'meta' => [],
        ];
    }
}
