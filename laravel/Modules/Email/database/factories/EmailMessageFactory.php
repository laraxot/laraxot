<?php

declare(strict_types=1);

namespace Modules\Email\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Email\Models\EmailMessage;

/**
 * @extends Factory<EmailMessage>
 */
class EmailMessageFactory extends Factory
{
    /** @var class-string<EmailMessage> */
    protected $model = EmailMessage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'conversation_id' => 1,
            'external_message_id' => $this->faker->unique()->uuid(),
            'direction' => 'inbound',
            'from_email' => $this->faker->safeEmail(),
            'from_name' => $this->faker->name(),
            'to_email' => $this->faker->safeEmail(),
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'status' => 'received',
            'processing_status' => 'pending',
            'payload' => [],
        ];
    }
}
