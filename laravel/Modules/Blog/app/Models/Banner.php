<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Blog\Database\Factories\BannerFactory;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Modules\Cms\Models\Menu.
 *
 * @property int                       $id
 * @property string                    $name
 * @property array<string, mixed>|null $items
 * @property Carbon|null               $created_at
 * @property Carbon|null               $updated_at
 * @property string|null               $updated_by
 * @property string|null               $created_by
 * @property Carbon|null               $deleted_at
 * @property string|null               $deleted_by
 *
 * @method static BannerFactory factory($count = null, $state = [])
 * @method static Builder|Menu  newModelQuery()
 * @method static Builder|Menu  newQuery()
 * @method static Builder|Menu  onlyTrashed()
 * @method static Builder|Menu  query()
 * @method static Builder|Menu  whereCreatedAt($value)
 * @method static Builder|Menu  whereCreatedBy($value)
 * @method static Builder|Menu  whereDeletedAt($value)
 * @method static Builder|Menu  whereDeletedBy($value)
 * @method static Builder|Menu  whereId($value)
 * @method static Builder|Menu  whereItems($value)
 * @method static Builder|Menu  whereName($value)
 * @method static Builder|Menu  whereUpdatedAt($value)
 * @method static Builder|Menu  whereUpdatedBy($value)
 * @method static Builder|Menu  withTrashed()
 * @method static Builder|Menu  withoutTrashed()
 *
 * @property string|null                                       $link
 * @property string|null                                       $title
 * @property string|null                                       $description
 * @property string|null                                       $action_text
 * @property string|null                                       $category_id
 * @property Carbon|null                                       $start_date
 * @property Carbon|null                                       $end_date
 * @property bool                                              $hot_topic
 * @property int|null                                          $open_markets_count
 * @property bool                                              $landing_banner
 * @property int|null                                          $pos
 * @property Category|null                                     $category
 * @property string                                            $desktop_thumbnail
 * @property string                                            $desktop_thumbnail_webp
 * @property string                                            $mobile_thumbnail
 * @property string                                            $mobile_thumbnail_webp
 * @property MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property int|null                                          $media_count
 *
 * @method static Builder|Banner whereActionText($value)
 * @method static Builder|Banner whereCategoryId($value)
 * @method static Builder|Banner whereDescription($value)
 * @method static Builder|Banner whereEndDate($value)
 * @method static Builder|Banner whereHotTopic($value)
 * @method static Builder|Banner whereLandingBanner($value)
 * @method static Builder|Banner whereLink($value)
 * @method static Builder|Banner whereOpenMarketsCount($value)
 * @method static Builder|Banner wherePos($value)
 * @method static Builder|Banner whereStartDate($value)
 * @method static Builder|Banner whereTitle($value)
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 *
 * @mixin Model
 *
 * @method static Banner|null             first()
 * @method static Collection<int, Banner> get()
 * @method static Banner                  create(array<string, mixed> $attributes = [])
 * @method static Banner                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Banner  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Banner  whereNotNull((string|Expression) $columns)
 * @method static int                     count(string $columns = '*')
 *
 * @property ProfileContract|null $deleter
 *
 * @mixin \Eloquent
 */
class Banner extends BaseModel implements HasMedia
{
    use InteractsWithMedia;
    // use HasTranslations;

    /**
     * Attributi assegnabili in massa (mass assignment).
     *
     * @var list<string>
     */
    protected $fillable = [
        // "id", //: 40,
        // "desktop_thumbnail",//: "https://My_Company-media-production.s3.amazonaws.com/cache/7a/9c/7a9c8f672e3499d573f24901280952f3.jpg",
        // "mobile_thumbnail",//: "https://My_Company-media-production.s3.amazonaws.com/cache/0d/0c/0d0cf75bd794283b4606e85cc30f0045.jpg",
        // "desktop_thumbnail_webp",//: "https://My_Company-media-production.s3.amazonaws.com/cache/64/3f/643f313db56c3735d15ae3eb1c27d5ad.webp",
        // "mobile_thumbnail_webp",//: "https://My_Company-media-production.s3.amazonaws.com/cache/14/8c/148c10ea338dfbe1bbd329e551afbfcf.webp",
        'link', // : "https://My_Company.com/q/category/99/usa",
        'title', // : "American Politics",
        'description', // : "Congress, White House, Elections and more",
        'action_text', // : "Make Your Forecasts",
        'category_id',
        /*
        "category",//: 99,
        "category_dict": {
            "id": 99,
            "title": "USA",
            "slug": "usa",
            "parent": 98,
            "in_leaderboard": false,
            "icon": null
        },
        */
        'start_date', // : null,
        'end_date', // : null,
        'hot_topic', // : false,
        'open_markets_count', // : 119,
        'landing_banner', // : false
        'pos',
    ];

    /** @var list<string> */
    protected $appends = [
        'desktop_thumbnail',
        'mobile_thumbnail',
        'desktop_thumbnail_webp',
        'mobile_thumbnail_webp',
    ];

    // /**
    //  * @var array<int, string>
    //  */
    // public $translatable = [
    //   'title',
    //   'short_description',
    //   'action_text'
    // ];

    /**
     * https://dev.to/npesado/convert-images-to-webp-4i06.
     */
    public function registerMediaConversions(?Media $media = null): void // $media is unused but part of interface
    {$this->addMediaConversion('cover')
                                // ->format(Manipulations::FORMAT_WEBP)
                        ->width(320)
                        ->height(200);
    }

    public function getDesktopThumbnailAttribute(): string
    {
        return $this->getFirstMediaUrl('banner');
    }

    public function getMobileThumbnailAttribute(): string
    {
        return $this->getFirstMediaUrl('banner');
    }

    public function getDesktopThumbnailWebpAttribute(): string
    {
        return $this->getFirstMediaUrl('banner');
        // $urlToFirstImage = $course->getFirstMediaUrl('images', 'cover');
    }

    public function getMobileThumbnailWebpAttribute(): string
    {
        return $this->getFirstMediaUrl('banner');
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getUrlCategoryPage(): string
    {
        if (null === $this->category) {
            return route('categories.index', ['lang' => app()->getLocale()]);
        }

        return route('category.view', ['lang' => app()->getLocale(), 'slug' => $this->category->slug]);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'uuid' => 'string',
            'desktop_thumbnail' => 'string',
            'mobile_thumbnail' => 'string',
            'desktop_thumbnail_webp' => 'string',
            'mobile_thumbnail_webp' => 'string',
            'link' => 'string',
            'title' => 'string',
            'description' => 'string',
            'action_text' => 'string',
            'category_id' => 'string',
            /*
        "category",//: 99,
        "category_dict": {
            "id": 99,
            "title": "USA",
            "slug": "usa",
            "parent": 98,
            "in_leaderboard": false,
            "icon": null
        },
        */
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'hot_topic' => 'boolean',
            'open_markets_count' => 'integer',
            'landing_banner' => 'boolean',
        ];
    }
}

/*
"id": 40,
    "desktop_thumbnail":
      "https://My_Company-media-production.s3.amazonaws.com/cache/7a/9c/7a9c8f672e3499d573f24901280952f3.jpg",
    "mobile_thumbnail":
      "https://My_Company-media-production.s3.amazonaws.com/cache/0d/0c/0d0cf75bd794283b4606e85cc30f0045.jpg",
    "desktop_thumbnail_webp":
      "https://My_Company-media-production.s3.amazonaws.com/cache/64/3f/643f313db56c3735d15ae3eb1c27d5ad.webp",
    "mobile_thumbnail_webp":
      "https://My_Company-media-production.s3.amazonaws.com/cache/14/8c/148c10ea338dfbe1bbd329e551afbfcf.webp",
    "link": "https://My_Company.com/q/category/99/usa",
    "title": "American Politics",
    "short_description": "Congress, White House, Elections and more",
    "action_text": "Make Your Forecasts",
    "category": 99,
    "category_dict": {
      "id": 99,
      "title": "USA",
      "slug": "usa",
      "parent": 98,
      "in_leaderboard": false,
      "icon": null
    },
    "end_date": null,
    "hot_topic": false,
    "open_markets_count": 119,
    "landing_banner": false
*/
