<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\Device;

/**
 * @extends Factory<Device>
 */
=======
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\Device;

>>>>>>> f548be94 (.)
=======
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\Device;

=======
use Modules\User\Models\Device;

/**
 * @extends Factory<Device>
 */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @var class-string<Device>
=======
     * @var class-string<Model>
>>>>>>> f548be94 (.)
=======
     * @var class-string<Model>
=======
     * @var class-string<Device>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected $model = Device::class;

    /**
     * Define the model's default state.
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
<<<<<<< HEAD
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function definition(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            'uuid' => fake()->uuid(),
            'mobile_id' => fake()->uuid(),
            'languages' => [fake()->languageCode(), fake()->languageCode()],
            'device' => fake()->randomElement(['iPhone', 'Android', 'Desktop']),
            'platform' => fake()->randomElement(['iOS', 'Android', 'Windows', 'macOS', 'Linux']),
            'browser' => fake()->randomElement(['Safari', 'Chrome', 'Firefox', 'Edge']),
            'version' => fake()->numerify('#.#.#'),
            'is_robot' => false,
            'robot' => null,
            'is_desktop' => false,
            'is_mobile' => true,
            'is_tablet' => false,
            'is_phone' => true,
=======
=======
>>>>>>> 87273113 (.)
            // 'id' => $this->faker->randomNumber(5),
            // 'mobile_id' => $this->faker->randomNumber(5),
            'device' => $this->faker->word,
            'platform' => $this->faker->word,
            'browser' => $this->faker->word,
            'version' => $this->faker->word,
            'is_robot' => $this->faker->boolean,
            'robot' => $this->faker->word,
            'is_desktop' => $this->faker->boolean,
            'is_mobile' => $this->faker->boolean,
            'is_tablet' => $this->faker->boolean,
            'is_phone' => $this->faker->boolean,
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            'uuid' => fake()->uuid(),
            'mobile_id' => fake()->uuid(),
            'languages' => [fake()->languageCode(), fake()->languageCode()],
            'device' => fake()->randomElement(['iPhone', 'Android', 'Desktop']),
            'platform' => fake()->randomElement(['iOS', 'Android', 'Windows', 'macOS', 'Linux']),
            'browser' => fake()->randomElement(['Safari', 'Chrome', 'Firefox', 'Edge']),
            'version' => fake()->numerify('#.#.#'),
            'is_robot' => false,
            'robot' => null,
            'is_desktop' => false,
            'is_mobile' => true,
            'is_tablet' => false,
            'is_phone' => true,
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ];
    }
}
