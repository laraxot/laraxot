<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\WhatsApp\Models\WhatsAppMessage;

/**
 * @extends Factory<WhatsAppMessage>
 */
class WhatsAppMessageFactory extends Factory
{
    /** @var class-string<WhatsAppMessage> */
    protected $model = WhatsAppMessage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'conversation_id' => 1,
            'meta_message_id' => $this->faker->unique()->uuid(),
            'direction' => 'inbound',
            'from_number' => '+39'.$this->faker->numerify('3#########'),
            'to_number' => '+39'.$this->faker->numerify('3#########'),
            'message_type' => 'text',
            'body' => $this->faker->sentence(),
            'status' => 'received',
            'processing_status' => 'pending',
            'payload' => [],
        ];
    }
}
