<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Support;

final class ThemeComposerSupport
{
    public function __construct(
        private readonly ThemeArticleSupport $articles = new ThemeArticleSupport(),
        private readonly ThemeCategorySupport $categories = new ThemeCategorySupport(),
        private readonly ThemeCatalogSupport $catalog = new ThemeCatalogSupport(),
        private readonly ThemeBannerSupport $banners = new ThemeBannerSupport(),
        private readonly ThemeSidebarRenderer $sidebar = new ThemeSidebarRenderer(),
    ) {
    }

    public function articles(): ThemeArticleSupport
    {
        return $this->articles;
    }

    public function categories(): ThemeCategorySupport
    {
        return $this->categories;
    }

    public function catalog(): ThemeCatalogSupport
    {
        return $this->catalog;
    }

    public function banners(): ThemeBannerSupport
    {
        return $this->banners;
    }

    public function sidebar(): ThemeSidebarRenderer
    {
        return $this->sidebar;
    }
}
