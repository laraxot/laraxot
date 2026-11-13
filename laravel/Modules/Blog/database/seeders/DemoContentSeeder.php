<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Modules\Blog\Database\Seeders\Demo\DemoArticleCatalog;
use Modules\Blog\Models\Article;
use Modules\Blog\Models\DemoCommentator;
use Modules\Blog\Models\Event;
use Modules\Blog\Models\Post;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;

/**
 * Novita' ed Eventi demo (Parental STI `type` = post | event), idempotente per slug.
 *
 * Contenuti: DemoArticleCatalog (it/en, immagini Design Comuni con credito nella didascalia). Commenti: thread con
 * risposte dai cittadini demo (DemoUsersSeeder, creati se mancano), solo se l'articolo non ne ha ancora; l'ultimo thread
 * resta in moderazione, cosi' la demo mostra entrambi gli stati. Nessuna categoria: la tabella `categories` e' condivisa
 * con le categorie delle segnalazioni, e una categoria del blog comparirebbe tra quelle dei ticket.
 * Fuori da local/testing/demo non scrive niente, come FixcityDemoSeeder.
 */
class DemoContentSeeder extends Seeder
{
    /** @var list<string> */
    private const array ALLOWED_ENVIRONMENTS = ['local', 'testing', 'demo'];

    /** @var array{it: string, en: string} */
    private const array IMAGE_CREDIT = ['it' => 'Foto: Asset Design Comuni (Developers Italia)', 'en' => 'Photo: Design Comuni assets (Developers Italia)'];

    /** @var list<array{0: string, 1: string}> */
    private const array THREADS = [
        ['Finalmente! Era una delle cose che segnalavamo da mesi.', 'Confermo, e la segnalazione risulta già chiusa nella mia area personale.'],
        ['Ci sarà anche un servizio per chi ha difficoltà motorie?', 'Sì, nella pagina è indicato che il luogo è accessibile.'],
        ['Ottima iniziativa, grazie per l\'aggiornamento.', ''],
    ];

    public function run(): void
    {
        if (! app()->environment(self::ALLOWED_ENVIRONMENTS)) {
            $this->command?->warn('DemoContentSeeder: ambiente non ammesso, nessun dato scritto.');

            return;
        }

        $citizens = $this->citizens();

        $posts = 0;
        foreach (DemoArticleCatalog::posts() as $index => $item) {
            $post = Post::query()->updateOrCreate(['slug' => $item['slug']], [
                ...$this->common($item['title'], $item['summary'], $item['body'], $item['image'], $item['featured']),
                'published_at' => now()->subDays($item['days_ago'])->setTime(9, 30),
            ]);
            $this->comments($post, $citizens, $index);
            $posts++;
        }

        $events = 0;
        foreach (DemoArticleCatalog::events() as $index => $item) {
            $start = now()->addDays($item['starts_in'])->setTime(18, 0);
            $event = Event::query()->updateOrCreate(['slug' => $item['slug']], [
                ...$this->common($item['title'], $item['summary'], $item['body'], $item['image'], $item['featured']),
                'published_at' => now()->subDays(max(1, 14 - $index)),
                'event_start_date' => $start,
                'event_end_date' => $start->copy()->addDays($item['lasts'] - 1)->setTime(23, 0),
            ]);
            $this->comments($event, $citizens, $index + 3);
            $events++;
        }

        if ($this->command instanceof Command) {
            $this->command->info("DemoContentSeeder: {$posts} notizie e {$events} eventi (it/en), con immagini Design Comuni e commenti.");
        }
    }

    /**
     * @return list<DemoCommentator>
     */
    private function citizens(): array
    {
        $query = User::query()->where('email', 'like', '%@fixcity.demo')->orderBy('email')->limit(12);
        if (! $query->exists()) {
            UserFactory::new()
                ->count(12)
                ->state(new Sequence(static fn (Sequence $sequence): array => [
                    'email' => 'citizen'.($sequence->index + 1).'@fixcity.demo',
                ]))
                ->create();
        }

        return array_values($query
            ->get()
            ->map(static fn (User $user): DemoCommentator => DemoCommentator::fromUser($user))
            ->all());
    }

    /**
     * @param  array{it: string, en: string}  $title
     * @param  array{it: list<string>, en: list<string>}  $body
     * @return array<string, mixed>
     */
    private function common(array $title, string $summary, array $body, string $image, bool $featured): array
    {
        $blocks = [];
        foreach ($body as $locale => $paragraphs) {
            $blocks[$locale] = [
                ...array_map(
                    static fn (string $text): array => ['type' => 'paragraph', 'data' => ['text' => '<p>'.e($text).'</p>']],
                    $paragraphs,
                ),
                // figura principale: ResolveArticleBodyAction la riconosce dall'URL e ne mostra il credito
                ['type' => 'image', 'data' => ['url' => DemoArticleCatalog::image($image), 'alt' => $title[$locale], 'credit' => self::IMAGE_CREDIT[$locale]]],
            ];
        }

        return [
            'title' => $title,
            'description' => $summary,
            'excerpt' => $summary,
            'main_image_url' => DemoArticleCatalog::image($image),
            'content_blocks' => $blocks,
            'sidebar_blocks' => [],
            'footer_blocks' => [],
            'is_featured' => $featured,
            'show_on_homepage' => $featured,
            'status' => 'published',
        ];
    }

    /**
     * @param  list<User>  $citizens
     */
    private function comments(Article $article, array $citizens, int $offset): void
    {
        if ($citizens === [] || $article->comments()->exists()) {
            return;
        }

        foreach (self::THREADS as $i => [$text, $reply]) {
            $author = $citizens[($offset + $i) % count($citizens)];
            $replier = $citizens[($offset + $i + 5) % count($citizens)];
            if (! $author instanceof CanComment || ! $replier instanceof CanComment) {
                continue;
            }
            // l'ultimo thread resta in moderazione: non compare nel front office
            $approved = $i < count(self::THREADS) - 1;
            $comment = $this->moderated($article->comment($text, $author), $approved);
            if ($reply !== '') {
                $this->moderated($comment->comment($reply, $replier), $approved);
            }
        }
    }

    /** Stato di moderazione esplicito, senza notifiche: non dipende dall'auto-approvazione configurata. */
    private function moderated(Comment $comment, bool $approved): Comment
    {
        $comment->forceFill(['approved_at' => $approved ? now() : null])->save();

        return $comment;
    }
}
