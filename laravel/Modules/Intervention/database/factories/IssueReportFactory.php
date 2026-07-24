<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\IssueReport;

/**
 * @extends Factory<IssueReport>
 */
class IssueReportFactory extends Factory
{
    /** @var class-string<IssueReport> */
    protected $model = IssueReport::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'intervention_id' => Intervention::factory(),
            'created_by_user_id' => 1,
            'body' => $this->faker->paragraph(),
        ];
    }
}
