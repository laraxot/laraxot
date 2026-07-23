<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Modules\Blog\Database\Factories\TextWidgetFactory;
use Modules\Media\Models\Media;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Webmozart\Assert\Assert;

/**
 * Modules\Blog\Models\TextWidget.
 *
 * @property int                         $id
 * @property string                      $key
 * @property string|null                 $image
 * @property string|null                 $title
 * @property string|null                 $content
 * @property int                         $active
 * @property Carbon|null                 $created_at
 * @property Carbon|null                 $updated_at
 * @property string|null                 $updated_by
 * @property string|null                 $created_by
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 *
 * @method static TextWidgetFactory  factory($count = null, $state = [])
 * @method static Builder|TextWidget newModelQuery()
 * @method static Builder|TextWidget newQuery()
 * @method static Builder|TextWidget onlyTrashed()
 * @method static Builder|TextWidget query()
 * @method static Builder|TextWidget whereActive($value)
 * @method static Builder|TextWidget whereContent($value)
 * @method static Builder|TextWidget whereCreatedAt($value)
 * @method static Builder|TextWidget whereCreatedBy($value)
 * @method static Builder|TextWidget whereId($value)
 * @method static Builder|TextWidget whereImage($value)
 * @method static Builder|TextWidget whereKey($value)
 * @method static Builder|TextWidget whereTitle($value)
 * @method static Builder|TextWidget whereUpdatedAt($value)
 * @method static Builder|TextWidget whereUpdatedBy($value)
 * @method static Builder|TextWidget withTrashed()
 * @method static Builder|TextWidget withoutTrashed()
 *
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder|TextWidget whereDeletedAt($value)
 * @method static Builder|TextWidget whereDeletedBy($value)
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 *
 * @mixin Model
 *
 * @method static TextWidget|null             first()
 * @method static Collection<int, TextWidget> get()
 * @method static TextWidget                  create(array<string, mixed> $attributes = [])
 * @method static TextWidget                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|TextWidget  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|TextWidget  whereNotNull((string|Expression) $columns)
 * @method static int                         count(string $columns = '*')
 *
 * @property ProfileContract|null $deleter
 *
 * @mixin \Eloquent
 */
class TextWidget extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    /**
     * Attributi assegnabili in massa (mass assignment).
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'image',
        'title',
        'content',
        'active',
    ];

    public static function getTitle(string $key): ?string
    {
        $widget = self::query()->where('key', $key)->first();

        if (! $widget) {
            return '';
        }

        return $widget->title;
    }

    public static function getContent(string $key): ?string
    {
        $widget = Cache::get('text-widget-'.$key, fn () => self::query()->where('key', $key)->first());

        if (! $widget) {
            return '';
        }
        Assert::isInstanceOf($widget, self::class, '['.__LINE__.']['.__FILE__.']');

        return $widget->content;
    }
}
