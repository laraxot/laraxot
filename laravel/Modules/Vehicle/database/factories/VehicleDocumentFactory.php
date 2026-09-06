<?php

declare(strict_types=1);

namespace Modules\Vehicle\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Document\Models\Media;
use Modules\Vehicle\Models\Vehicle;
use Modules\Vehicle\Models\VehicleDocument;
use Modules\Xot\Actions\Cast\SafeIntCastAction;

/**
 * @extends Factory<VehicleDocument>
 */
class VehicleDocumentFactory extends Factory
{
    /** @var class-string<VehicleDocument> */
    protected $model = VehicleDocument::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'vehicle_id' => Vehicle::factory(),
            'media_id' => fn (array $attributes): int => SafeIntCastAction::cast(Media::query()->create([
                'mediable_type' => Vehicle::class,
                'mediable_id' => $attributes['vehicle_id'],
                'kind' => 'document',
                'disk' => 'public',
                'path' => 'media/vehicles/'.$this->faker->uuid().'.pdf',
            ])->getKey()),
            'title' => $this->faker->optional()->sentence(3),
        ];
    }
}
