<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Platform\Models\AuditLog;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    /** @var class-string<AuditLog> */
    protected $model = AuditLog::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_at' => now(),
            'actor_user_id' => (string) Str::uuid(),
            'action' => $this->faker->randomElement(['created', 'updated', 'deleted', 'viewed', 'restored']),
            'subject_type' => 'Modules\\'.$this->faker->word().'\\Models\\'.ucfirst($this->faker->word()),
            'subject_id' => (string) $this->faker->numberBetween(1, 10000),
            'panel_id' => $this->faker->optional()->randomElement(['admin', 'app', 'user']),
            'route' => $this->faker->optional()->slug(2),
            'ip' => $this->faker->optional()->ipv4(),
            'user_agent' => $this->faker->optional()->userAgent(),
            'before' => null,
            'after' => ['field' => $this->faker->word(), 'value' => $this->faker->word()],
        ];
    }
}
