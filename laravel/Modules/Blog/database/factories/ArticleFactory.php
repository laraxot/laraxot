<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Factories;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Blog\Models\Article;

use function Safe\preg_replace;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Article>
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = preg_replace('/\./', '', fake()->sentence(6));
        $title = is_string($title) ? $title : implode('', $title);
        $image = '/themes/Sixteen/design-comuni/assets/images/notizia-sicurezza.png';
        $body = 'Contenuto di prova per ambienti di sviluppo: sostituire con testi curati tramite DemoContentSeeder.';

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'main_image_url' => $image,
            'content_blocks' => [
                ['type' => 'title', 'data' => ['text' => $title, 'level' => 'h2']],
                ['type' => 'paragraph', 'data' => ['text' => '<p>'.$body.'</p>']],
                ['type' => 'image', 'data' => [
                    'url' => $image,
                    'alt' => 'Immagine di prova del tema Sixteen',
                    'caption' => 'Asset locale — non usare Lorem Ipsum in demo pubbliche.',
                ]],
            ],
            'footer_blocks' => [],
            'sidebar_blocks' => [],
            'description' => 'Sintesi di prova senza Lorem Ipsum.',
            // `type` = alias Parental STI: post (Post) | event (Event)
            'type' => 'post',
        ];
    }

    /**
     * Post editoriale demo (type=post) — testi civici + immagine Design Comuni.
     */
    public function demoArticle(): static
    {
        return $this->demoPost();
    }

    /**
     * Post editoriale demo (agnostico: novità/guida/storytelling).
     */
    public function demoPost(): static
    {
        $title = 'Come preparare una segnalazione utile';
        $image = '/themes/Sixteen/design-comuni/assets/images/img-disservizio-thumbnail.png';
        $body = 'Indica indirizzo, contesto e una fotografia leggibile: l’ufficio può verificare prima e intervenire meglio.';

        return $this->state([
            'slug' => Str::slug($title).'-'.Str::random(5),
            'title' => $title,
            'description' => 'Tre informazioni bastano a rendere una segnalazione azionabile.',
            'excerpt' => 'Tre informazioni bastano a rendere una segnalazione azionabile.',
            'type' => 'post',
            'status' => 'published',
            'published_at' => now()->subDays(7),
            'closed_at' => null,
            'main_image_url' => $image,
            'content_blocks' => [
                ['type' => 'title', 'data' => ['text' => $title, 'level' => 'h2']],
                ['type' => 'paragraph', 'data' => ['text' => '<p>'.$body.'</p>']],
                ['type' => 'image', 'data' => [
                    'url' => $image,
                    'alt' => 'Cittadino che documenta un problema nello spazio pubblico',
                    'caption' => 'Una foto chiara accelera la presa in carico.',
                    'credit' => 'Asset Design Comuni / tema Sixteen — uso demo FixCity',
                ]],
            ],
            'sidebar_blocks' => [],
            'footer_blocks' => [],
        ]);
    }

    /**
     * Evento demo = Article con type=event (nessun modulo Event).
     * Non toccare la colonna Parental `type`.
     */
    public function demoEvent(CarbonInterface $startsAt, CarbonInterface $endsAt): static
    {
        $title = 'Laboratorio civico: segnalare bene in cinque minuti';
        $image = '/themes/Sixteen/design-comuni/assets/images/eventi-biblioteca-parco.png';
        $body = 'Un appuntamento pratico con smartphone: posizione, descrizione e fotografia rispettosa della privacy.';

        return $this->state([
            'slug' => Str::slug($title).'-'.Str::random(5),
            'title' => $title,
            'description' => 'Imparare insieme a usare il servizio digitale comunale.',
            'excerpt' => 'Imparare insieme a usare il servizio digitale comunale.',
            'type' => 'event',
            'status' => 'published',
            'published_at' => now()->subDays(3),
            'event_start_date' => $startsAt,
            'event_end_date' => $endsAt,
            'closed_at' => null,
            'main_image_url' => $image,
            'content_blocks' => [
                ['type' => 'title', 'data' => ['text' => $title, 'level' => 'h2']],
                ['type' => 'paragraph', 'data' => ['text' => '<p>'.$body.'</p>']],
                ['type' => 'image', 'data' => [
                    'url' => $image,
                    'alt' => 'Laboratorio civico sull’uso dello smartphone',
                    'caption' => 'Formazione pratica per cittadini e associazioni.',
                    'credit' => 'Asset Design Comuni / tema Sixteen — uso demo FixCity',
                ]],
            ],
            'sidebar_blocks' => [],
            'footer_blocks' => [],
        ]);
    }
}
