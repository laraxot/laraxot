<?php

declare(strict_types=1);

use Illuminate\Support\Facades\RateLimiter;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use Modules\Blog\Models\Article;
use Modules\Comment\Actions\Comment\EnsureCommentRateLimitAction;
use Modules\Comment\Filament\Widgets\Commentable\CommentsWidget;
use Modules\Comment\Models\Comment;
use Modules\Comment\Tests\Support\CommentatorUser;
use Modules\Xot\Actions\Cast\SafeStringCastAction;

uses(\Modules\Comment\Tests\TestCase::class);

/*
 * Il widget dei commenti cosi' come lo monta il front office (CMT-022), con le risposte attive (noReplies = false) come in
 * Themes/Sixteen/resources/views/components/blocks/{blog,events,ticket-detail}.
 * Gia' successo: con le risposte attive la pagina di dettaglio rispondeva 500 («Cannot assign array to property
 * CommentWidget::$uiConfig») appena l'articolo aveva un commento; e il form di scrittura legava `ui.text`, una proprieta' che il
 * widget non ha, quindi nessun utente riusciva a pubblicare. Nessun test montava il widget.
 */

/**
 * @return array<string, mixed>
 */
function commentsWidgetParams(Article $article, bool $readOnly = false): array
{
    return [
        'commentableType' => $article::class,
        'commentableKey' => SafeStringCastAction::cast($article->getKey()),
        'readOnly' => $readOnly,
        'hideNotifications' => true,
        'noReplies' => false,
        'noReactions' => true,
    ];
}

beforeEach(function (): void {
    RateLimiter::clear('comment-submit:'.request()->ip());
});

it('renders an existing comment when replies are enabled', function (): void {
    $article = Article::factory()->create();
    Comment::factory()->forArticle($article)->create(['text' => 'Testo del primo commento', 'original_text' => 'Testo del primo commento']);

    Livewire::test(CommentsWidget::class, commentsWidgetParams($article, readOnly: true))
        ->assertOk()
        ->assertSee('Testo del primo commento');
});

it('lists a reply once, under its parent, even when it was stored with the article as commentable', function (): void {
    $article = Article::factory()->create();
    $parent = Comment::factory()->forArticle($article)->create(['text' => 'Commento padre', 'original_text' => 'Commento padre']);
    Comment::factory()->forArticle($article)->create(['parent_id' => $parent->getKey(), 'text' => 'Risposta unica', 'original_text' => 'Risposta unica']);

    Livewire::test(CommentsWidget::class, commentsWidgetParams($article, readOnly: true))
        ->assertSeeText('Risposta unica', escape: false)
        ->assertSeeInOrder(['Commento padre', 'Risposta unica']);

    $html = Livewire::test(CommentsWidget::class, commentsWidgetParams($article, readOnly: true))->html();
    expect(substr_count($html, 'Risposta unica'))->toBe(1);
});

it('posts a top-level comment through the Filament form field', function (): void {
    $article = Article::factory()->create();
    $author = CommentatorUser::createOne();

    $component = Livewire::actingAs($author)->test(CommentsWidget::class, commentsWidgetParams($article));
    $component->set('data.text', 'Un commento di prova')->call('comment');
    $component->assertHasNoErrors();
    $component->assertSet('data.text', null);

    $saved = Comment::query()->where('commentable_id', $article->getKey())->sole();
    expect($saved->original_text)->toBe('Un commento di prova')
        ->and($saved->parent_id)->toBeNull();
});

it('rejects an empty or too short comment with a field error', function (): void {
    $article = Article::factory()->create();

    $component = Livewire::actingAs(CommentatorUser::createOne())->test(CommentsWidget::class, commentsWidgetParams($article));
    $component->set('data.text', '')->call('comment');
    $component->assertHasErrors(['data.text' => 'required']);
    $component->set('data.text', 'x')->call('comment');
    $component->assertHasErrors(['data.text']);

    expect(Comment::query()->where('commentable_id', $article->getKey())->count())->toBe(0);
});

it('answers 403 to a guest who calls comment() directly, not a server error', function (): void {
    $article = Article::factory()->create();

    Livewire::test(CommentsWidget::class, commentsWidgetParams($article, readOnly: true))
        ->set('data.text', 'Commento da ospite')
        ->call('comment')
        ->assertForbidden();

    expect(Comment::query()->where('commentable_id', $article->getKey())->count())->toBe(0);
});

it('keeps the Markdown as source and never lets scripts or javascript links reach the stored HTML', function (): void {
    $article = Article::factory()->create();

    Livewire::actingAs(CommentatorUser::createOne())
        ->test(CommentsWidget::class, commentsWidgetParams($article))
        ->set('data.text', "**ciao** <script>alert(1)</script> <img src=x onerror=alert(2)> [clic](javascript:alert(3))")
        ->call('comment')
        ->assertHasNoErrors();

    $saved = Comment::query()->where('commentable_id', $article->getKey())->sole();
    expect((string) $saved->text)->toContain('<strong>ciao</strong>')
        ->not->toContain('<script')
        ->not->toContain('onerror')
        ->not->toContain('javascript:');
});

it('throttles a user who posts more than the allowed number of comments per window', function (): void {
    $article = Article::factory()->create();
    $widget = Livewire::actingAs(CommentatorUser::createOne())->test(CommentsWidget::class, commentsWidgetParams($article));

    foreach (range(1, EnsureCommentRateLimitAction::MAX_ATTEMPTS) as $n) {
        $widget->set('data.text', "Commento numero {$n}")->call('comment')->assertHasNoErrors();
    }

    $widget->set('data.text', 'Commento di troppo')->call('comment')->assertHasErrors(['data.text']);

    expect(Comment::query()->where('commentable_id', $article->getKey())->count())->toBe(EnsureCommentRateLimitAction::MAX_ATTEMPTS);
});

it('does not let the browser repoint the widget to another commentable', function (): void {
    $article = Article::factory()->create();

    Livewire::actingAs(CommentatorUser::createOne())
        ->test(CommentsWidget::class, commentsWidgetParams($article))
        ->set('commentableKey', 'altro-articolo');
})->throws(CannotUpdateLockedPropertyException::class);
