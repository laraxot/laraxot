<?php

declare(strict_types=1);

use Modules\Blog\Models\Article;
use Modules\Blog\Tests\TestCase;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/*
<<<<<<< HEAD
 * Integrazione User <-> Blog: utenti (connessione `user`) e articoli (connessione del Blog, nei test alias della connessione
 * predefinita) convivono nello stesso test e nella stessa transazione. Spostato da laravel/tests/Integration (regola: i test vivono nei moduli). La versione precedente
 * usava annotazioni `@test` (ignorate da PHPUnit 12) e colonne inesistenti (`published`, `blog_articles`): non girava mai.
 */
it('stores users and articles side by side', function (): void {
    $user = User::factory()->create();
    $article = Article::factory()->create();

=======
 * Integrazione User <-> Blog: utenti (connessione `user`) e articoli (connessione `blog`) convivono nello stesso test e
 * nella stessa transazione. Spostato da laravel/tests/Integration (regola: i test vivono nei moduli). La versione precedente
 * usava annotazioni `@test` (ignorate da PHPUnit 12) e colonne inesistenti (`published`, `blog_articles`): non girava mai.
 */
it('stores users and articles on their own connections', function (): void {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    Assert::assertSame('blog', $article->getConnectionName());
>>>>>>> c007baa (.)
    Assert::assertTrue(User::query()->whereKey($user->getKey())->exists());
    Assert::assertTrue(Article::query()->whereKey($article->getKey())->exists());
});

it('keeps the authenticated user while working with blog articles', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Article::factory()->create();

    $this->assertAuthenticatedAs($user);
});

it('filters published articles by status', function (): void {
    $before = Article::query()->where('status', 'published')->count();

    Article::factory()->count(2)->create(['status' => 'published', 'published_at' => now()->subDay()]);

    Assert::assertSame($before + 2, Article::query()->where('status', 'published')->count());
});
