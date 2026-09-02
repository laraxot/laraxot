<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Modules\Blog\Actions\Article\FilterArticleContentBlocksExceptAction;
use Modules\Blog\Actions\Article\FilterArticleContentBlocksOnlyAction;
use Modules\Blog\Actions\Article\FormatArticleHumanReadTimeAction;
use Modules\Blog\Actions\Article\FormatArticlePublishedDateAction;
use Modules\Blog\Actions\Article\FormatArticleTimeLeftForHumansAction;
use Modules\Blog\Actions\Article\ResolveArticleMainImageFromAttributesAction;
use Modules\Blog\Actions\Article\ResolveArticleMainImageUrlAction;
use Modules\Blog\Actions\Article\ResolveArticleThumbnailAction;
use Modules\Blog\Actions\Article\ResolveArticleTranslationAction;
use Modules\Blog\Database\Factories\ArticleFactory;
use Modules\Blog\Models\Concerns\ArticleFeedable;
use Modules\Blog\Models\Concerns\ArticleQueryScopes;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\CommentNotificationSubscription;
use Modules\Comment\Models\Concerns\HasComments;
use Modules\Comment\Models\Contracts\SupportsCommentNotifications;
use Modules\Lang\Models\Contracts\HasTranslationsContract;
use Modules\Rating\Models\Rating;
use Modules\Rating\Models\RatingMorph;
use Modules\Rating\Models\Traits\HasRating;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Parental\HasChildren;
use Spatie\Feed\Feedable;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

/**
 * Modules\Blog\Models\Article.
 *
 * @property Profile|null                $author
 * @property Collection<int, Category>   $categories
 * @property int|null                    $categories_count
 * @property Collection<int, Comment>    $comments
 * @property int|null                    $comments_count
 * @property string                      $human_read_time
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 * @property Collection<int, Tag>        $tags
 * @property Collection<int, Status>     $statuses
 * @property int|null                    $statuses_count
 * @property int|null                    $tags_count
 * @property UserContract|null           $user
 * @property string                      $body
 * @property Carbon                      $published_at
 * @property Carbon                      $updated_at
 * @property string                      $slug
 * @property string                      $title
 * @property string                      $description
 * @property string                      $main_image_upload
 * @property string                      $main_image_url
 * @property array<string, mixed>|string $content_blocks
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Article article(string $id)
 * @method static \Illuminate\Database\Eloquent\Builder|Article author(string $profile_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Article category(string $id)
 * @method static \Illuminate\Database\Eloquent\Builder|Article currentStatus(...$names)
 * @method static \Illuminate\Database\Eloquent\Builder|Article differentFromCurrentArticle(string $current_article)
 * @method static ArticleFactory                                factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Article otherCurrentStatus(...$names)
 * @method static \Illuminate\Database\Eloquent\Builder|Article published()
 * @method static \Illuminate\Database\Eloquent\Builder|Article publishedUntilToday()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article search(string $searching)
 * @method static \Illuminate\Database\Eloquent\Builder|Article showHomepage()
 * @method static \Illuminate\Database\Eloquent\Builder|Article tag(string $id)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withAllTags((\ArrayAccess<int|string, Tag>|Tag|array<int|string, Tag>|string) $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withAllTagsOfAnyType(array<int|string, Tag>|string $tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withAnyTags((\ArrayAccess<int|string, Tag>|Tag|array<int|string, Tag>|string) $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withAnyTagsOfAnyType(array<int|string, Tag>|string $tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Article withoutTags((\ArrayAccess<int|string, Tag>|Tag|array<int|string, Tag>|string) $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withoutTrashed()
 *
 * @property string                          $id
 * @property string                          $uuid
 * @property string|null                     $content
 * @property string|null                     $picture
 * @property int|null                        $category_id
 * @property int|null                        $author_id
 * @property string|null                     $status
 * @property int                             $show_on_homepage
 * @property int|null                        $read_time
 * @property string|null                     $excerpt
 * @property string                          $created_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null                     $updated_by
 * @property string|null                     $created_by
 * @property string|null                     $deleted_by
 * @property array<string, mixed>|null       $footer_blocks
 * @property array<string, mixed>|null       $sidebar_blocks
 * @property int                             $is_featured
 * @property string|null                     $closed_at
 * @property Category|null                   $category
 * @property string                          $main_image
 * @property Collection<int, Rating>         $ratings
 * @property int|null                        $ratings_count
 * @property mixed                           $translations
 * @property string|null                     $rewarded_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereContentBlocks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereFooterBlocks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereLocales(string $column, array<string, mixed> $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereMainImageUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereMainImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereReadTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereShowOnHomepage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUuid($value)
 *
 * @property int         $status_display
 * @property string|null $bet_end_date
 * @property string|null $event_start_date
 * @property string|null $event_end_date
 * @property int         $is_wagerable
 * @property int|null    $wagers_count
 * @property int|null    $wagers_count_canonical
 * @property int|null    $wagers_count_total
 * @property int|null    $wagers
 * @property string|null $brier_score
 * @property string|null $brier_score_play_money
 * @property string|null $brier_score_real_money
 * @property float|null  $volume_play_money
 * @property float|null  $volume_real_money
 * @property int         $is_following
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBetEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBrierScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBrierScorePlayMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBrierScoreRealMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereEventEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereEventStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsFollowing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsWagerable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSidebarBlocks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereStatusDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereVolumePlayMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereVolumeRealMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereWagers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereWagersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereWagersCountCanonical($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereWagersCountTotal($value)
 *
 * @property RatingMorph $pivot
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereJsonContainsLocales(string $column, array<string, mixed> $locales, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereRewardedAt($value)
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 *
 * @mixin Model
 *
 * @property string|null                                      $type
 * @property string|null                                      $extra
 * @property string|null                                      $resolved_at
 * @property string|null                                      $liquidity
 * @property float|null                                       $stocks_count
 * @property float|null                                       $stocks_value
 * @property string                                           $sum_credit_yes
 * @property string                                           $sum_credit_no
 * @property int                                              $count_credit_yes
 * @property int                                              $count_credit_no
 * @property Collection<int, CommentNotificationSubscription> $notificationSubscriptions
 * @property int|null                                         $notification_subscriptions_count
 *
 * @method static EloquentBuilder<static>|Article                       whereCountCreditNo($value)
 * @method static EloquentBuilder<static>|Article                       whereCountCreditYes($value)
 * @method static EloquentBuilder<static>|Article                       whereExtra($value)
 * @method static EloquentBuilder<static>|Article                       whereLiquidity($value)
 * @method static EloquentBuilder<static>|Article                       whereResolvedAt($value)
 * @method static EloquentBuilder<static>|Article                       whereStocksCount($value)
 * @method static EloquentBuilder<static>|Article                       whereStocksValue($value)
 * @method static EloquentBuilder<static>|Article                       whereSumCreditNo($value)
 * @method static EloquentBuilder<static>|Article                       whereSumCreditYes($value)
 * @method static EloquentBuilder<static>|Article                       whereType($value)
 * @method static EloquentBuilder<static>|Article                       withAnyTagsOfType(array<string, mixed>|string $type)
 * @method static Article|null                                          first()
 * @method static Collection<int, Article>                              get()
 * @method static Article                                               create(array<string, mixed> $attributes = [])
 * @method static Article                                               firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article where((string|\Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereNotNull((string|\Illuminate\Contracts\Database\Query\Expression) $columns)
 * @method static int                                                   count(string $columns = '*')
 *
 * @property ProfileContract|null $deleter
 *
 * @method static EloquentBuilder<static>|Article childrenWith(array<string, mixed> $relations)
 * @method static EloquentBuilder<static>|Article childrenWithCount(array<string, mixed> $relations)
 *
 * @mixin \Eloquent
 */
class Article extends BaseModel implements Feedable, HasTranslationsContract, SupportsCommentNotifications
{
    use ArticleFeedable;
    use ArticleQueryScopes;
    use HasChildren;
    use HasComments;
    use HasRating;
    use HasTags;
    use HasTranslations;

    /** @var array<int, string> */
    public array $translatable = [
        'title',
        // 'description',
        'content_blocks',
        'sidebar_blocks',
        'footer_blocks',
    ];

    /**
     * Attributi assegnabili in massa (mass assignment).
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'slug',
        // 'description',
        'body',
        'images',
        'viewCount',

        'content_blocks',
        'footer_blocks',
        'sidebar_blocks',
        'is_featured',
        'main_image_upload',
        'main_image_url',
        'published_at',
        'closed_at',
        'category_id',
        'type',
        // 'is_closed', => closet_at

        /*
        'title',
        'slug',
        'thumbnail',
        'body',
        'user_id',
        'active',
        'published_at',
        'meta_title',
        'meta_description',

         'author_id',
        'title',
        'slug',
        'content',
        'picture',
        'category_id',
        'status',
        'publish_date',
        'show_on_homepage',
        'author_name',
        'read_time',
        'excerpt',
        */
        'status',
        'status_display',
        'bet_end_date',
        'event_start_date',
        'event_end_date',
        'is_wagerable',
        'brier_score',
        'brier_score_play_money',
        'brier_score_real_money',
        'wagers_count',
        'wagers_count_canonical',
        'wagers_count_total',
        'wagers',
        'volume_play_money',
        'volume_real_money',
        'is_following',
        'rewarded_at',
    ];

    /**
     * Ottiene la traduzione di un attributo in una specifica lingua.
     *
     * @param string $key               Il nome dell'attributo da tradurre
     * @param string $locale            Il codice della lingua richiesta
     * @param bool   $useFallbackLocale Se utilizzare o meno la lingua di fallback
     *
     * @return array<int|string, mixed>|string|int|null Il valore tradotto dell'attributo
     *
     * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
     */
    public function getTranslation(string $key, string $locale, bool $useFallbackLocale = true): array|string|int|null
    {
        return app(ResolveArticleTranslationAction::class)->execute($this, $key, $locale, $useFallbackLocale);
    }

    /**
     * Restituisce tutti i feed item.
     *
     * @return Collection<int, Article>
     */
    public static function getAllFeedItems(): Collection
    {
        /** @var Collection<int, Article> $result */
        $result = static::latest()->take(150)->get();

        return $result;
    }

    /**
     * Wrapper statico per latest() richiesto da PHPStan.
     *
     * @return EloquentBuilder<Article>
     */
    public static function latest(?string $column = null): EloquentBuilder
    {
        $column = $column ?? self::CREATED_AT;

        /** @var EloquentBuilder<Article> $query */
        $query = static::query()->latest($column);

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    /** @return BelongsTo<Model&UserContract, $this> */
    public function user(): BelongsTo
    {
        $userClass = XotData::make()->getUserClass();

        return $this->belongsTo($userClass);
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ----- Feed ------
    public function shortBody(int $words = 30): string
    {
        return Str::words(strip_tags((string) $this->body), $words);
    }

    public function getFormattedDate(): string
    {
        return app(FormatArticlePublishedDateAction::class)->execute($this);
    }

    public function getThumbnail(): ?string
    {
        return app(ResolveArticleThumbnailAction::class)->execute($this);
    }

    /**
     * @return Attribute<string, never>
     */
    public function humanReadTime(): Attribute
    {
        return Attribute::make(
            get: static function (mixed $value, array $attributes): string {
                unset($value);

                return app(FormatArticleHumanReadTimeAction::class)->execute($attributes);
            },
        );
    }

    /**
     * The author that belong to the article.
     *
     * @return BelongsTo<Profile, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'user_id'); // ->withTrashed();
    }

    public function getTitle(): string
    {
        if ($this->title) {
            return $this->title;
        }

        return 'Get Title of article id '.$this->id;
    }

    public function getMainImage(): string
    {
        return app(ResolveArticleMainImageUrlAction::class)->execute($this);
    }

    /*
     * NO !!
    protected function createdAt(): Attribute
    {
        return new Attribute(
            get: static function ($value): string {
                return date_format(new DateTime($value), 'd/m/Y');
            }
        );
    }
    */

    public function getUuidAttribute(?string $value): string
    {
        if (null !== $value && '' !== $value) {
            return $value;
        }
        // dddx($value);
        $value = (string) Str::uuid();
        $this->uuid = $value;
        $this->save();

        // return $value;

        return '##';
    }

    // public function getTimeLeft(): string
    // {
    //     $time = $this->closed_at;

    //     $days = Carbon::now()->diffInDays($time);
    //     $hours = Carbon::now()->copy()->addDays($days)->diffInHours($time);
    //     $minutes = Carbon::now()->copy()->addDays($days)->addHours($hours)->diffInMinutes($time);
    //     return $days.'d'.$hours.'m'.$minutes.'s';
    // }

    public function getTimeLeftForHumans(): ?string
    {
        return app(FormatArticleTimeLeftForHumansAction::class)->execute($this);
    }

    // /**
    //  * Get the path to the picture
    //  *
    //  * @return string
    //  */
    // public function path()
    // {
    //     return "/storage/{$this->picture}";
    // }
    // /**
    //  * Get the route key for the article.
    //  *
    //  * @return string
    //  */
    // public function getRouteKeyName()
    // {
    //     if (inAdmin()) {
    //         return $this->getKeyName();
    //     }
    //     return 'slug';
    // }
    /**
     * Get the path key to the item for the frontend only.
     */
    public function getFrontRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @param array<int, string> $nameBlocks
     *
     * @return array<int, array<string, mixed>>
     */
    public function getOnlyContentBlocks(array $nameBlocks): array
    {
        return app(FilterArticleContentBlocksOnlyAction::class)->execute($this, $nameBlocks);
    }

    /**
     * @param array<int, string> $nameBlocks
     *
     * @return array<int, array<string, mixed>>
     */
    public function getExceptContentBlocks(array $nameBlocks): array
    {
        return app(FilterArticleContentBlocksExceptAction::class)->execute($this, $nameBlocks);
    }

    /**
     * This string will be used in notifications on what a new comment
     * was made.
     */
    public function commentableName(): string
    {
        return 'Predizione';
    }

    /**
     * This URL will be used in notifications to let the user know
     * where the comment itself can be read.
     */
    public function commentUrl(): string
    {
        return '#';
    }

    /**
     * Converti l'attributo 'closed_at' in un oggetto Carbon.
     */
    public function getClosedAtAttribute(string $value): Carbon
    {
        return Carbon::parse($value);
    }

    /**
     * Attributi assegnabili in massa (mass assignment).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'uuid' => 'string',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'main_image_upload' => 'string',
            'main_image_url' => 'string',
        ];
    }

    // /**
    //  * Get the tags of the article
    //  *
    //  * @return \Illuminate\Database\Eloquent\Collection
    //  */
    // public function tags()
    // {
    //    return $this->belongsToMany(Tag::class);
    // }

    /**
     * @return Attribute<string, never>
     */
    protected function mainImage(): Attribute
    {
        return Attribute::make(
            get: static function (mixed $value, array $attributes): string {
                unset($value);

                return app(ResolveArticleMainImageFromAttributesAction::class)->execute($attributes);
            },
        );
    }
}
