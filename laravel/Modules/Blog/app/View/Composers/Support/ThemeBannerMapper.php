<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Support;

use Modules\Blog\Models\Banner;
use Modules\UI\Datas\SliderData;

final class ThemeBannerMapper
{
    /**
     * @return list<SliderData>
     */
    public function all(): array
    {
        $results = Banner::all()->sortBy('pos');
        $tmp = [];
        foreach ($results as $content) {
            $sliderData = $content->toArray();
            $sliderData['link'] = $content->getUrlCategoryPage();
            $tmp[] = SliderData::from($sliderData);
        }

        return $tmp;
    }

    public function single(Banner $banner): SliderData
    {
        return SliderData::from($banner->toArray());
    }
}
