<?php

declare(strict_types=1);

namespace Modules\HR\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Document\Models\Media;
use Modules\HR\Models\EmployeeDocument;

/**
 * @extends Factory<EmployeeDocument>
 */
class EmployeeDocumentFactory extends Factory
{
    /** @var class-string<EmployeeDocument> */
    protected $model = EmployeeDocument::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => EmployeeFactory::new(),
            'media_id' => Media::factory(),
            'title' => $this->faker->words(2, true),
            'kind' => $this->faker->randomElement(['contract', 'id_card', 'certificate']),
        ];
    }
}
