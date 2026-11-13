<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Modules\Blog\Models\Article;
use Modules\Comment\Models\Comment;
use Modules\Comment\Tests\Support\CommentatorUser;
use Tests\TestCase;

uses(TestCase::class);

/*
 * CMT-022: il dettaglio del blog monta il widget dei commenti con le risposte attive. Con un commento in archivio quel ramo rispondeva 500
 * («Cannot assign array to property CommentWidget::$uiConfig») e nessun test montava il blocco del tema: la pagina era rotta in produzione
 * senza che niente diventasse rosso. Qui il blocco viene reso davvero, come lo rende la pagina Folio `[container0]/[slug0]`.
 */

/**
 * @return array{article: Article, parent: Comment, reply: Comment}
 */
function blogDetailWithThread(): array
{
    $article = Article::factory()->create();
    $parent = Comment::factory()->forArticle($article)->create(['text' => 'Commento padre', 'original_text' => 'Commento padre']);
    $reply = Comment::factory()->forArticle($article)->create(['parent_id' => $parent->getKey(), 'text' => 'Risposta annidata', 'original_text' => 'Risposta annidata']);

    return ['article' => $article, 'parent' => $parent, 'reply' => $reply];
}

it('renders the thread with its replies for a guest and sends them to the login with a way back', function (): void {
    ['article' => $article] = blogDetailWithThread();
    Auth::logout();

    $html = view('pub_theme::components.blocks.blog.detail', ['post' => $article])->render();

    expect($html)
        ->toContain('Commento padre')
        ->toContain('Risposta annidata')
        ->toContain('cmt-replies')
        ->not->toContain('wire:click="startReplying"')
        ->toContain('redirect=');
});

it('offers a reply button and the editor to a logged-in citizen', function (): void {
    ['article' => $article] = blogDetailWithThread();
    $this->actingAs(CommentatorUser::createOne());

    $html = view('pub_theme::components.blocks.blog.detail', ['post' => $article])->render();

    expect($html)
        ->toContain('Commento padre')
        ->toContain('wire:click="startReplying"')
        ->toContain('fi-fo-markdown-editor');
});
