<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Intervention\Models\DailyNote;

/**
 * @extends Factory<DailyNote>
 */
class DailyNoteFactory extends Factory
{
    /** @var class-string<DailyNote> */
    protected $model = DailyNote::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'note_date' => now()->toDateString(),
            'body' => $this->faker->paragraph(),
            'body_html' => '<p>'.$this->faker->paragraph().'</p>',
        ];
    }
}
