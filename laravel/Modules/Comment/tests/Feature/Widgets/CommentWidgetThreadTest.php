<?php

declare(strict_types=1);

use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Modules\Blog\Models\Article;
use Modules\Comment\Filament\Widgets\Comment\CommentWidget;
use Modules\Comment\Models\Comment;
use Modules\Comment\Tests\Support\CommentatorUser;

uses(\Modules\Comment\Tests\TestCase::class);

/*
 * Il singolo commento (CMT-022): «Rispondi» a quel commento, modifica ed eliminazione, con editor Markdown Filament aperto solo su richiesta.
 * Il ramo delle risposte non era mai stato eseguito: ha mostrato cinque difetti il giorno in cui `noReplies` e' passato a false.
 */

/**
 * @return array<string, mixed>
 */
function threadParams(Comment $comment): array
{
    return ['comment' => $comment, 'writable' => true, 'showReplies' => true, 'showAvatar' => true];
}

function threadComment(Article $article, ?CommentatorUser $author = null, ?Comment $parent = null, string $text = 'Testo del commento'): Comment
{
    $state = ['text' => $text, 'original_text' => $text, 'parent_id' => $parent?->getKey()];
    if ($author instanceof CommentatorUser) {
        $state += ['commentator_id' => $author->getKey(), 'commentator_type' => $author->getMorphClass()];
    }

    return Comment::factory()->forArticle($article)->create($state);
}

beforeEach(function (): void {
    config(['comments.ui.max_reply_depth' => 3, 'commenti.threaded' => true]);
    RateLimiter::clear('comment-submit:'.request()->ip());
});

it('shows a reply button and keeps the editor closed until the reader asks for it', function (): void {
    $article = Article::factory()->create();
    $comment = threadComment($article);

    Livewire::actingAs(CommentatorUser::createOne())
        ->test(CommentWidget::class, threadParams($comment))
        ->assertSee(__('comment::txt.reply'))
        ->assertDontSeeHtml('fi-fo-markdown-editor')
        ->call('startReplying')
        ->assertSeeHtml('fi-fo-markdown-editor')
        ->call('stopReplying')
        ->assertDontSeeHtml('fi-fo-markdown-editor');
});

it('publishes a reply under the single comment it answers', function (): void {
    $article = Article::factory()->create();
    $parent = threadComment($article, text: 'Commento a cui rispondere');
    $other = threadComment($article, text: 'Un altro commento');
    $replier = CommentatorUser::createOne();

    $component = Livewire::actingAs($replier)->test(CommentWidget::class, threadParams($parent));
    $component->call('startReplying')->set('data.text', 'Una risposta puntuale')->call('reply');
    $component->assertHasNoErrors();
    $component->assertSee('Una risposta puntuale');
    $component->assertDontSeeHtml('fi-fo-markdown-editor');

    $reply = Comment::query()->where('parent_id', $parent->getKey())->sole();
    expect($reply->original_text)->toBe('Una risposta puntuale')
        ->and(Comment::query()->where('parent_id', $other->getKey())->count())->toBe(0);
});

it('answers 403 to a guest who tries to open or send a reply', function (): void {
    $article = Article::factory()->create();
    $comment = threadComment($article);

    Livewire::test(CommentWidget::class, threadParams($comment))
        ->assertDontSee(__('comment::txt.reply'))
        ->call('startReplying')
        ->assertForbidden();

    Livewire::test(CommentWidget::class, threadParams($comment))
        ->set('data.text', 'Risposta da ospite')
        ->call('reply')
        ->assertForbidden();

    expect(Comment::query()->where('parent_id', $comment->getKey())->count())->toBe(0);
});

it('rejects an empty reply with a field error and saves nothing', function (): void {
    $article = Article::factory()->create();
    $comment = threadComment($article);

    Livewire::actingAs(CommentatorUser::createOne())
        ->test(CommentWidget::class, threadParams($comment))
        ->call('startReplying')
        ->set('data.text', '')
        ->call('reply')
        ->assertHasErrors(['data.text' => 'required']);

    expect(Comment::query()->where('parent_id', $comment->getKey())->count())->toBe(0);
});

it('does not offer or accept a reply deeper than the configured depth', function (): void {
    $article = Article::factory()->create();
    $level0 = threadComment($article);
    $level1 = threadComment($article, parent: $level0);
    $level2 = threadComment($article, parent: $level1);
    $level3 = threadComment($article, parent: $level2);

    // Oltre il limite il pulsante non c'e', e una chiamata diretta di Livewire riceve 403 (il browser non decide la profondita').
    Livewire::actingAs(CommentatorUser::createOne())
        ->test(CommentWidget::class, threadParams($level3))
        ->assertDontSee(__('comment::txt.reply'))
        ->call('startReplying')
        ->assertForbidden();

    Livewire::actingAs(CommentatorUser::createOne())
        ->test(CommentWidget::class, threadParams($level2))
        ->assertSee(__('comment::txt.reply'));
});

it('offers no reply at all when threading is switched off', function (): void {
    config(['commenti.threaded' => false]);
    $article = Article::factory()->create();
    $comment = threadComment($article);

    Livewire::actingAs(CommentatorUser::createOne())
        ->test(CommentWidget::class, threadParams($comment))
        ->assertDontSee(__('comment::txt.reply'))
        ->call('startReplying')
        ->assertForbidden();
});

it('lets the author edit their own comment and refreshes the stored HTML', function (): void {
    $article = Article::factory()->create();
    $author = CommentatorUser::createOne();
    $comment = threadComment($article, $author, text: 'Testo originale');

    Livewire::actingAs($author)
        ->test(CommentWidget::class, threadParams($comment))
        ->call('startEditing')
        ->assertSet('data.text', 'Testo originale')
        ->set('data.text', 'Testo **corretto**')
        ->call('edit')
        ->assertHasNoErrors();

    $fresh = $comment->fresh();
    expect($fresh?->original_text)->toBe('Testo **corretto**')
        ->and((string) $fresh?->text)->toContain('<strong>corretto</strong>');
});

it('does not let another user edit or delete a comment that is not theirs', function (): void {
    $article = Article::factory()->create();
    $comment = threadComment($article, CommentatorUser::createOne(), text: 'Non e tuo');
    $intruder = CommentatorUser::createOne();

    Livewire::actingAs($intruder)
        ->test(CommentWidget::class, threadParams($comment))
        ->assertDontSee(__('comment::txt.edit'))
        ->call('startEditing')
        ->assertForbidden();

    Livewire::actingAs($intruder)
        ->test(CommentWidget::class, threadParams($comment))
        ->call('delete')
        ->assertForbidden();

    expect($comment->fresh())->not->toBeNull()
        ->and($comment->fresh()?->original_text)->toBe('Non e tuo');
});

it('lets the author delete their own comment', function (): void {
    $article = Article::factory()->create();
    $author = CommentatorUser::createOne();
    $comment = threadComment($article, $author);

    Livewire::actingAs($author)
        ->test(CommentWidget::class, threadParams($comment))
        ->call('delete')
        ->assertHasNoErrors();

    expect(Comment::query()->whereKey($comment->getKey())->exists())->toBeFalse();
});
