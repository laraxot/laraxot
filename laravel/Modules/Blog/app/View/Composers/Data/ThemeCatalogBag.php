<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Data;

use Illuminate\Support\Collection;
use Modules\Blog\Models\Profile;
use Modules\Blog\Models\Tag;

final class ThemeCatalogBag
{
    /**
     * @return Collection<int, Profile>
     */
    public function footerAuthors(): Collection
    {
        return Profile::inRandomOrder()->limit(8)->get();
    }

    /**
     * @return Collection<int, Tag>
     */
    public function tags(): Collection
    {
        return Tag::all();
    }

    /**
     * @return Collection<int, Tag>
     */
    public function footerTags(): Collection
    {
        return Tag::take(15)->get();
    }
}
