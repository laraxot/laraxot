<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Composition;

use Modules\Blog\View\Composers\Data\ThemeArticleBag;
use Modules\Blog\View\Composers\Data\ThemeBannerBag;
use Modules\Blog\View\Composers\Data\ThemeCatalogBag;
use Modules\Blog\View\Composers\Data\ThemeCategoryBag;
use Modules\Blog\View\Composers\Rendering\ThemeSidebarRenderer;

final class ThemeComposerBag
{
    public function __construct(
        private readonly ThemeArticleBag $articles = new ThemeArticleBag(),
        private readonly ThemeCategoryBag $categories = new ThemeCategoryBag(),
        private readonly ThemeCatalogBag $catalog = new ThemeCatalogBag(),
        private readonly ThemeBannerBag $banners = new ThemeBannerBag(),
        private readonly ThemeSidebarRenderer $sidebar = new ThemeSidebarRenderer(),
    ) {
    }

    public function articles(): ThemeArticleBag
    {
        return $this->articles;
    }

    public function categories(): ThemeCategoryBag
    {
        return $this->categories;
    }

    public function catalog(): ThemeCatalogBag
    {
        return $this->catalog;
    }

    public function banners(): ThemeBannerBag
    {
        return $this->banners;
    }

    public function sidebar(): ThemeSidebarRenderer
    {
        return $this->sidebar;
    }
}
