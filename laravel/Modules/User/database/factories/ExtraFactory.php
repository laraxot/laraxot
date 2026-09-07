<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\Extra;
use Modules\Xot\Database\Factories\BaseExtraFactory;

/**
 * La forma del dato sta in {@see BaseExtraFactory}, nel modulo che possiede il
 * concetto. Qui si dichiara **solo** il modello: e' quello che porta con se' la
 * connection di questo modulo.
 *
 * @extends BaseExtraFactory<Extra>
 */
class ExtraFactory extends BaseExtraFactory
{
    /** @var class-string<Extra> */
    protected $model = Extra::class;
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\Extra;

class ExtraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Model>
     */
    protected $model = Extra::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // 'user_id' => $this->faker->randomNumber(5),
            'name' => $this->faker->name,
            'personal_team' => $this->faker->boolean,
        ];
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Models\Extra;
use Modules\Xot\Database\Factories\BaseExtraFactory;

/**
 * La forma del dato sta in {@see BaseExtraFactory}, nel modulo che possiede il
 * concetto. Qui si dichiara **solo** il modello: e' quello che porta con se' la
 * connection di questo modulo.
 *
 * @extends BaseExtraFactory<Extra>
 */
class ExtraFactory extends BaseExtraFactory
{
    /** @var class-string<Extra> */
    protected $model = Extra::class;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
