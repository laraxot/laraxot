<?php

declare(strict_types=1);

namespace Modules\Notify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Notify\Models\MailTemplate;

/**
 * @extends Factory<MailTemplate>
 */
class MailTemplateFactory extends Factory
{
    protected $model = MailTemplate::class;
<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return array<string, mixed>
     */
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    /**
     * @return array<string, mixed>
     */
>>>>>>> a988596b (first)

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->slug(),
            'subject' => $this->faker->sentence(),
            'html_template' => $this->faker->randomHtml(),
            'text_template' => $this->faker->text(),
            'type' => $this->faker->randomElement(['email', 'notification', 'sms']),
            'is_active' => $this->faker->boolean(80),
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_at' => $this->faker->dateTimeBetween('-1 year')];
=======
            'updated_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_at' => $this->faker->dateTimeBetween('-1 year')];
>>>>>>> a988596b (first)
    }
}
