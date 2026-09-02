<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Support;

use Modules\Blog\Models\Banner;
use Modules\UI\Datas\SliderData;
use Webmozart\Assert\Assert;

final class ThemeBannerSupport
{
    public function __construct(
        private readonly ThemeBannerMapper $bannerMapper = new ThemeBannerMapper(),
    ) {
    }

    /**
     * @return list<SliderData>
     */
    public function all(): array
    {
        return $this->bannerMapper->all();
    }

    public function single(object $banner): SliderData
    {
        Assert::isInstanceOf($banner, Banner::class);

        return $this->bannerMapper->single($banner);
    }
}
