<?php

declare(strict_types=1);

namespace Modules\Comment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\Article;
use Modules\Comment\Models\Comment;
use Modules\User\Models\User;

use function Safe\json_encode;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Comment>
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $text = 'Grazie per la guida chiara: ora so quali dettagli inserire nella segnalazione.';

        return [
            'text' => $text,
            'original_text' => $text,
            'commentable_type' => Article::class,
            'commentable_id' => Article::factory(),
            'commentator_type' => User::class,
            'commentator_id' => User::factory(),
            'parent_id' => null,
            'approved_at' => now(),
            'extra' => json_encode(['source' => 'demo', 'locale' => 'it']),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Commento demo legato a un articolo/evento Blog.
     */
    public function forArticle(Article $article): static
    {
        return $this->state(fn (array $attributes): array => [
            'commentable_type' => $article->getMorphClass(),
            'commentable_id' => $article->getKey(),
        ]);
    }

    public function demoCivic(): static
    {
        $text = 'Condivido: indicare quartiere e fascia oraria rende la verifica più semplice.';

        return $this->state([
            'text' => $text,
            'original_text' => $text,
            'approved_at' => now(),
        ]);
    }

    /**
     * Indica che il commento è approvato.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes): array => [
            'approved_at' => now(),
        ]);
    }

    /**
     * Indica che il commento è in attesa di approvazione.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => [
            'approved_at' => null,
        ]);
    }

    /**
     * Indica che il commento è una risposta a un altro commento.
     */
    public function asReply(Comment $parentComment): static
    {
        return $this->state(fn (array $attributes): array => [
            'parent_id' => $parentComment->id,
            'commentable_type' => $parentComment->commentable_type,
            'commentable_id' => $parentComment->commentable_id,
        ]);
    }
}
