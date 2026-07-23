<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Support;

use Illuminate\Contracts\Support\Renderable;
use Modules\Blog\Models\Article;
use Modules\UI\View\Components\Render\Blocks;
use Webmozart\Assert\Assert;

final class ThemeSidebarRenderer
{
    public function showArticleSidebarContent(string $slug): Renderable
    {
        Assert::isInstanceOf($article = Article::firstOrCreate(['slug' => $slug], ['sidebar_blocks' => []]), Article::class, '['.__LINE__.']['.__FILE__.']');

        $sidebarBlocks = $article->sidebar_blocks ?? [];

        return (new Blocks(
            view: 'ui::components.render.blocks.v1',
            blocks: is_array($sidebarBlocks) ? $sidebarBlocks : [],
            model: $article,
        ))->render();
    }
}
