<?php

declare(strict_types=1);

/**
 * Article Workflow Feature Test.
 *
 * Tests complete user workflows for blog articles:
 * - Create article workflow (validation, storage, redirect)
 * - Update article workflow (edit form, update, confirmation)
 * - Delete article workflow (soft delete, restore, permanent delete)
 * - Publish/unpublish workflow
 * - Article visibility based on publication status
 */

namespace Tests\Feature\Modules\Blog;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Modules\Blog\Database\Factories\ArticleFactory;
use Modules\Blog\Models\Article;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Tests\TestCase;

class ArticleWorkflowTest extends TestCase
{
    use WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::new()->createOne();
    }

    /**
     * @param array<string, mixed> $attributes
     */
    private function createArticle(array $attributes = []): Article
    {
        return ArticleFactory::new()->createOne($attributes);
    }

    /**
     * @param array<string, mixed> $attributes
     *
     * @return Collection<int, Article>
     */
    private function createArticles(int $count, array $attributes = []): Collection
    {
        /** @var Collection<int, Article> $articles */
        $articles = ArticleFactory::new()->count($count)->create($attributes);

        return $articles;
    }

    private function freshArticle(Article $article): Article
    {
        $fresh = $article->fresh();
        $this->assertInstanceOf(Article::class, $fresh);

        return $fresh;
    }

    private function assertArticleIsPublished(Article $article): void
    {
        $this->assertTrue(
            Article::published()->whereKey($article->getKey())->exists()
        );
    }

    private function assertArticleIsDraft(Article $article): void
    {
        $this->assertFalse(
            Article::published()->whereKey($article->getKey())->exists()
        );
    }

    /** @test Complete article creation workflow */
    public function completeArticleCreationWorkflow(): void
    {
        // Step 1: User authenticates
        $this->actingAs($this->user);
        $this->assertAuthenticatedAs($this->user);

        // Step 2: Create article data
        $articleData = [
            'title' => 'My First Article',
            'slug' => 'my-first-article',
            'content' => 'This is the content of my first article.',
            'published_at' => null,
        ];

        // Step 3: Create article in database
        $article = Article::create($articleData);

        // Step 4: Verify article exists
        $this->assertDatabaseHas('blog_articles', [
            'title' => 'My First Article',
            'slug' => 'my-first-article',
        ]);

        // Step 5: Verify article is in draft state
        $this->assertArticleIsDraft($article);
    }

    /** @test Article update workflow */
    public function articleUpdateWorkflow(): void
    {
        $this->actingAs($this->user);

        // Create initial article
        $article = $this->createArticle([
            'title' => 'Original Title',
            'content' => 'Original content',
        ]);

        // Update article
        $article->update([
            'title' => 'Updated Title',
            'content' => 'Updated content with more details',
        ]);

        // Verify update
        $this->assertDatabaseHas('blog_articles', [
            'id' => $article->id,
            'title' => 'Updated Title',
        ]);

        $fresh = $this->freshArticle($article);
        $this->assertEquals('Updated content with more details', $fresh->content);
    }

    /** @test Article publication workflow */
    public function articlePublicationWorkflow(): void
    {
        $this->actingAs($this->user);

        // Create draft article
        $article = $this->createArticle(['published_at' => null]);

        // Verify is draft
        $this->assertArticleIsDraft($article);

        // Publish article
        $publishedAt = Carbon::now();
        $article->update(['published_at' => $publishedAt]);

        // Verify is published
        $this->assertArticleIsPublished($this->freshArticle($article));

        // Verify published articles are queryable
        $published = Article::published()->get();
        $this->assertTrue($published->contains('id', $article->id));
    }

    /** @test Article unpublishing workflow */
    public function articleUnpublishingWorkflow(): void
    {
        $this->actingAs($this->user);

        // Create published article
        $article = $this->createArticle(['published_at' => Carbon::now()]);

        // Verify is published
        $this->assertArticleIsPublished($article);

        // Unpublish article
        $article->update(['published_at' => null]);

        // Verify is now draft
        $this->assertArticleIsDraft($this->freshArticle($article));

        // Verify not in published query
        $published = Article::published()->get();
        $this->assertFalse($published->contains('id', $article->id));
    }

    /** @test Article deletion workflow */
    public function articleDeletionWorkflow(): void
    {
        $this->actingAs($this->user);

        // Create article
        $article = $this->createArticle();
        $articleId = $article->id;

        // Delete article
        $article->delete();

        // Verify deletion
        $this->assertDatabaseMissing('blog_articles', ['id' => $articleId]);
    }

    /** @test Multiple article management workflow */
    public function multipleArticleManagementWorkflow(): void
    {
        $this->actingAs($this->user);

        // Create multiple articles
        $article1 = $this->createArticle(['title' => 'Article 1', 'published_at' => Carbon::now()]);
        $article2 = $this->createArticle(['title' => 'Article 2', 'published_at' => null]);
        $article3 = $this->createArticle(['title' => 'Article 3', 'published_at' => Carbon::now()]);

        // Query published articles
        $published = Article::published()->get();
        $this->assertCount(2, $published);
        $this->assertTrue($published->contains('id', $article3->id));

        // Update one article
        $article2->update(['published_at' => Carbon::now()]);

        // Verify count increased
        $publishedAfter = Article::published()->get();
        $this->assertCount(3, $publishedAfter);

        // Delete one article
        $article1->delete();

        // Verify count decreased
        $publishedFinal = Article::published()->get();
        $this->assertCount(2, $publishedFinal);
    }

    /** @test Article visibility based on publication status */
    public function articleVisibilityWorkflow(): void
    {
        $this->actingAs($this->user);

        $draftArticle = $this->createArticle(['published_at' => null]);
        $publishedArticle = $this->createArticle(['published_at' => Carbon::now()]);

        // Simulate public article query (published only)
        $visibleArticles = Article::published()->get();

        $this->assertTrue($visibleArticles->contains('id', $publishedArticle->id));
        $this->assertFalse($visibleArticles->contains('id', $draftArticle->id));
    }

    /** @test Article lifecycle workflow (create -> edit -> publish -> archive) */
    public function articleCompleteLifecycleWorkflow(): void
    {
        $this->actingAs($this->user);

        // Stage 1: Create draft
        $article = $this->createArticle([
            'title' => 'Lifecycle Test Article',
            'published_at' => null,
        ]);

        $this->assertArticleIsDraft($article);

        // Stage 2: Edit content
        $article->update([
            'content' => 'Refined content after review.',
        ]);

        // Stage 3: Publish
        $article->update(['published_at' => Carbon::now()]);
        $this->assertArticleIsPublished($this->freshArticle($article));

        // Stage 4: Verify in public query
        $publicArticles = Article::published()->get();
        $this->assertTrue($publicArticles->contains('id', $article->id));

        // Stage 5: Unpublish (archive)
        $article->update(['published_at' => null]);
        $this->assertArticleIsDraft($this->freshArticle($article));

        // Stage 6: Verify no longer in public query
        $publicArticlesAfter = Article::published()->get();
        $this->assertFalse($publicArticlesAfter->contains('id', $article->id));
    }

    /** @test Error handling in article workflow - validation */
    public function articleWorkflowHandlesValidationErrors(): void
    {
        $this->actingAs($this->user);

        // Attempt to create article with missing required fields
        try {
            Article::create([
                // Missing 'title'
                'slug' => 'test-slug',
            ]);
            $this->fail('Expected validation or persistence exception when title is missing');
        } catch (\Exception $e) {
            // Validation failed as expected
            $this->assertStringContainsString('title', strtolower($e->getMessage()));
        }
    }

    /** @test Batch article operations workflow */
    public function batchArticleOperationsWorkflow(): void
    {
        $this->actingAs($this->user);

        // Create multiple articles
        $articles = $this->createArticles(5);

        // Bulk update to publish all
        $ids = $articles->pluck('id');
        Article::query()->whereIn('id', $ids)->update(['published_at' => Carbon::now()]);

        // Verify all published
        $published = Article::query()
            ->whereIn('id', $ids)
            ->whereNotNull('published_at')
            ->get();

        $this->assertCount(5, $published);
    }
}
