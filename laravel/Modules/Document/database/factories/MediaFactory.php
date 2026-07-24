<?php

declare(strict_types=1);

namespace Modules\Document\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Document\Models\Media;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /** @var class-string<Media> */
    protected $model = Media::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mediable_type' => 'Modules\Customer\\Models\\Customer',
            'mediable_id' => 1,
            'uploaded_by_user_id' => null,
            'kind' => 'document',
            'title' => $this->faker->optional()->sentence(3),
            'disk' => 'public',
            'path' => 'media/'.$this->faker->uuid().'.pdf',
            'mime_type' => 'application/pdf',
            'size_bytes' => $this->faker->numberBetween(1000, 500000),
            'meta' => [],
        ];
    }
}
