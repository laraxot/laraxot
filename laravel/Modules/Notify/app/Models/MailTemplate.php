<?php

declare(strict_types=1);

namespace Modules\Notify\Models;

// use Spatie\LaravelPackageTools\Concerns\Package\HasTranslations;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Exception;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
<<<<<<< HEAD
=======
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Database\Eloquent\Builder;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Spatie\MailTemplates\Interfaces\MailTemplateInterface;
use Spatie\MailTemplates\Models\MailTemplate as SpatieMailTemplate;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

/**
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
 * @property-read list<string> $translatable_columns_from
 * @property-read array<string, mixed> $variables
 * @property-read mixed $translations
=======
 * @property-read list<string> $translatable_columns_from
 * @property-read array<string, mixed> $variables
 * @property-read mixed $translations
 *
>>>>>>> a988596b (first)
 * @method static Builder<static>|MailTemplate forMailable(\Illuminate\Contracts\Mail\Mailable $mailable)
 * @method static Builder<static>|MailTemplate newModelQuery()
 * @method static Builder<static>|MailTemplate newQuery()
 * @method static Builder<static>|MailTemplate query()
 * @method static Builder<static>|MailTemplate whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|MailTemplate whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|MailTemplate whereLocale(string $column, string $locale)
 * @method static Builder<static>|MailTemplate whereLocales(string $column, array<int, string> $locales)
<<<<<<< HEAD
=======
 *
>>>>>>> a988596b (first)
 * @property int $id
 * @property string|null $name
 * @property string|null $mailable
 * @property string|null $slug
 * @property string|array<array-key, mixed>|null $subject
 * @property string|array<array-key, mixed>|null $html_template
 * @property string|array<array-key, mixed>|null $text_template
 * @property string|int $version
 * @property string|null $params
 * @property array<array-key, mixed>|null $sms_template
 * @property int $counter
 * @property string|null $html_layout_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
<<<<<<< HEAD
 * @method static Builder<static>|MailTemplate whereCounter($value)
=======
 * @property int $id
 * @property string $mailable
 * @property string|null $subject
 * @property string|null $html_layout_path
 * @property string $html_template
 * @property string|null $text_template
 * @property int $version
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_by
 * @property string $name
 * @property string $slug
 * @property array<string, mixed> $variables
 * @property array<string, array<string, mixed>> $translations
 *
 * @method static Builder<static>|MailTemplate forMailable(Mailable $mailable)
 * @method static Builder<static>|MailTemplate newModelQuery()
 * @method static Builder<static>|MailTemplate newQuery()
 * @method static Builder<static>|MailTemplate query()
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 *
 * @method static Builder<static>|MailTemplate whereCounter($value)
>>>>>>> a988596b (first)
 * @method static Builder<static>|MailTemplate whereCreatedAt($value)
 * @method static Builder<static>|MailTemplate whereCreatedBy($value)
 * @method static Builder<static>|MailTemplate whereDeletedAt($value)
 * @method static Builder<static>|MailTemplate whereDeletedBy($value)
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
 * @method static Builder<static>|MailTemplate whereHtmlLayoutPath($value)
 * @method static Builder<static>|MailTemplate whereHtmlTemplate($value)
 * @method static Builder<static>|MailTemplate whereId($value)
 * @method static Builder<static>|MailTemplate whereMailable($value)
 * @method static Builder<static>|MailTemplate whereName($value)
 * @method static Builder<static>|MailTemplate whereParams($value)
 * @method static Builder<static>|MailTemplate whereSlug($value)
 * @method static Builder<static>|MailTemplate whereSmsTemplate($value)
<<<<<<< HEAD
=======
 * @method static Builder<static>|MailTemplate whereHtmlTemplate($value)
 * @method static Builder<static>|MailTemplate whereId($value)
 * @method static Builder<static>|MailTemplate whereJsonContainsLocale(string $column, string $locale, mixed $value, string $operand = '=')
 * @method static Builder<static>|MailTemplate whereJsonContainsLocales(string $column, array<int, string> $locales, mixed $value, string $operand = '=')
 * @method static Builder<static>|MailTemplate whereLocale(string $column, string $locale)
 * @method static Builder<static>|MailTemplate whereLocales(string $column, array<int, string> $locales)
 * @method static Builder<static>|MailTemplate whereMailable($value)
 * @method static Builder<static>|MailTemplate whereName($value)
 * @method static Builder<static>|MailTemplate whereSlug($value)
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
 * @method static Builder<static>|MailTemplate whereSubject($value)
 * @method static Builder<static>|MailTemplate whereTextTemplate($value)
 * @method static Builder<static>|MailTemplate whereUpdatedAt($value)
 * @method static Builder<static>|MailTemplate whereUpdatedBy($value)
<<<<<<< HEAD
<<<<<<< HEAD
 * @method static Builder<static>|MailTemplate whereVersion($value)
=======
 *
 * @property array<int, string>|null $params
 *
 * @method static Builder<static>|MailTemplate whereParams($value)
 *
 * @property array<string, mixed>|null $sms_template
 * @property array<string, mixed>|null $whatsapp_template
 * @property int $counter
 *
 * @method static Builder<static>|MailTemplate whereCounter($value)
 * @method static Builder<static>|MailTemplate whereSmsTemplate($value)
 * @method static Builder<static>|MailTemplate whereWhatsappTemplate($value)
 * @method static Builder<static>|MailTemplate whereHtmlLayoutPath($value)
 * @method static Builder<static>|MailTemplate whereVersion($value)
 *
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 * @method static Builder<static>|MailTemplate whereVersion($value)
 *
>>>>>>> a988596b (first)
=======
 * @property-read array $translatable_columns_from
 * @property-read array $variables
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\MailTemplate forMailable(\Illuminate\Contracts\Mail\Mailable $mailable)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\MailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\MailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\MailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\MailTemplate whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\MailTemplate whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\MailTemplate whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\MailTemplate whereLocales(string $column, array $locales)
>>>>>>> 98d0a12c (.)
=======
 * @property-read list<string> $translatable_columns_from
 * @property-read array<string, mixed> $variables
 * @property-read mixed $translations
 *
 * @method static Builder<static>|MailTemplate forMailable(\Illuminate\Contracts\Mail\Mailable $mailable)
 * @method static Builder<static>|MailTemplate newModelQuery()
 * @method static Builder<static>|MailTemplate newQuery()
 * @method static Builder<static>|MailTemplate query()
 * @method static Builder<static>|MailTemplate whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|MailTemplate whereJsonContainsLocales(string $column, array<int, string> $locales, ?mixed $value, string $operand = '=')
 * @method static Builder<static>|MailTemplate whereLocale(string $column, string $locale)
 * @method static Builder<static>|MailTemplate whereLocales(string $column, array<int, string> $locales)
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $mailable
 * @property string|null $slug
 * @property string|array<array-key, mixed>|null $subject
 * @property string|array<array-key, mixed>|null $html_template
 * @property string|array<array-key, mixed>|null $text_template
 * @property string|int $version
 * @property string|null $params
 * @property array<array-key, mixed>|null $sms_template
 * @property int $counter
 * @property string|null $html_layout_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder<static>|MailTemplate whereCounter($value)
 * @method static Builder<static>|MailTemplate whereCreatedAt($value)
 * @method static Builder<static>|MailTemplate whereCreatedBy($value)
 * @method static Builder<static>|MailTemplate whereDeletedAt($value)
 * @method static Builder<static>|MailTemplate whereDeletedBy($value)
 * @method static Builder<static>|MailTemplate whereHtmlLayoutPath($value)
 * @method static Builder<static>|MailTemplate whereHtmlTemplate($value)
 * @method static Builder<static>|MailTemplate whereId($value)
 * @method static Builder<static>|MailTemplate whereMailable($value)
 * @method static Builder<static>|MailTemplate whereName($value)
 * @method static Builder<static>|MailTemplate whereParams($value)
 * @method static Builder<static>|MailTemplate whereSlug($value)
 * @method static Builder<static>|MailTemplate whereSmsTemplate($value)
 * @method static Builder<static>|MailTemplate whereSubject($value)
 * @method static Builder<static>|MailTemplate whereTextTemplate($value)
 * @method static Builder<static>|MailTemplate whereUpdatedAt($value)
 * @method static Builder<static>|MailTemplate whereUpdatedBy($value)
 * @method static Builder<static>|MailTemplate whereVersion($value)
 *
>>>>>>> a377e9e6 (.)
 * @mixin \Eloquent
 */
class MailTemplate extends SpatieMailTemplate implements MailTemplateInterface
{
    use HasSlug;

    // use SoftDeletes;
    use HasTranslations;

    /** @var list<string> */
    public array $translatable = ['subject', 'html_template', 'text_template', 'sms_template'];

    protected $connection = 'notify';

    /** @var list<string> */
    protected $fillable = [
        'mailable',
        'name',
        'slug',
        'subject',
        'html_layout_path',
        'html_template',
        'text_template',
        'sms_template',
        'whatsapp_template',
        // 'version',  //under development
        'params',
<<<<<<< HEAD
<<<<<<< HEAD
        'counter'];
=======
        'counter',
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'counter'];
>>>>>>> a988596b (first)

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('subject')
            ->saveSlugsTo('slug');
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForMailable(Builder $query, Mailable $mailable): Builder
    {
        if (! method_exists($mailable, 'getSlug')) {
            throw new Exception('Il metodo getSlug() non è definito nella classe '.$mailable::class);
        }
        $slug = $mailable->getSlug();

        return $query->where('mailable', $mailable::class)->where('slug', $slug);
    }

    /**
     * Define attribute casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
<<<<<<< HEAD
<<<<<<< HEAD
            'deleted_at' => 'datetime'];
=======
            'deleted_at' => 'datetime',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'deleted_at' => 'datetime'];
>>>>>>> a988596b (first)
    }
}
