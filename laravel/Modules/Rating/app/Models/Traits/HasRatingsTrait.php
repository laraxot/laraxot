<?php

declare(strict_types=1);

namespace Modules\Rating\Models\Traits;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\Rating\Contracts\RatingsFormCallerContract;
use Modules\Rating\Filament\Concerns\DecoratesRatingFormFields;
use Modules\Rating\Models\BaseRating;
use Modules\Rating\Models\Rating;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Webmozart\Assert\Assert;

/**
 * Trait HasRatingsTrait — rating polimorfi su modelli host.
 *
 * Perché: un solo punto per relazioni, sync per extra_attributes, regole validazione.
 * Consumer: `@use HasRatingsTrait<static>` sulla classe host.
 *
 * @template TModel of Model
 *
 * @phpstan-require-extends Model
 */
trait HasRatingsTrait
{
    /**
     * Le righe pivot della valutazione, **entrambe le forme di `model_type`**.
     *
     * `rating_morph.model_type` contiene per la stessa entità sia l'alias della morph
     * map sia il FQCN del model, a seconda di come è stata scritta la riga. Misurato
     * su un'installazione: 180 righe con l'alias (2 record) e 2.045 con il FQCN
     * (228 record). `ratings()` è una `morphToMany` e vede **solo** `getMorphClass()`,
     * cioè l'alias: chi ci aggrega sopra conta 2 record su 230 e mostra un numero
     * sbagliato che sembra giusto.
     *
     * Questa relazione esiste per leggere lo stato reale finché i dati non sono
     * normalizzati. **È una misura di transizione, non il modello giusto**: la cura è
     * un `UPDATE` che porta `model_type` all'alias ovunque, e va decisa da chi possiede
     * i dati. Vedi la story `rating-morph-model-type-doppio`.
     *
     * Aggrega qui e non su `ratings()` anche per un secondo motivo: `value` sta sul
     * pivot, non su `ratings`, quindi `sum('ratings', 'value')` è un errore SQL
     * (`Unknown column 'ratings.value'`).
     *
     * @return HasMany<MorphPivot, TModel>
     */
    public function ratingMorphs(): HasMany
    {
        $pivot = $this->guessMorphPivot(Rating::getClassName());

        /** @var HasMany<MorphPivot, TModel> $relation */
        $relation = $this->hasMany($pivot::class, 'model_id', $this->getKeyName())
            ->whereIn('model_type', array_unique([$this->getMorphClass(), static::class]));

        return $relation;
    }

    /**
     * @return MorphToMany<BaseRating, TModel, MorphPivot, 'pivot'>
     */
    public function ratings(): MorphToMany
    {
        /** @var class-string<BaseRating> $related */
        $related = Rating::getClassName();
        Assert::subclassOf($related, BaseRating::class);

        /** @var MorphToMany<BaseRating, TModel, MorphPivot, 'pivot'> $relation */
        $relation = $this->morphToManyX($related, 'model');

        $relation = $relation->ordered();

        return $relation;
    }

    /**
     * Obiettivi rating con aggregati (count, avg, voto utente corrente).
     *
     * @return HasMany<BaseRating, TModel>
     */
    public function ratingObjectives(): HasMany
    {
        $userId = Auth::id();

        /** @var class-string<BaseRating> $related */
        $related = Rating::getClassName();
        Assert::subclassOf($related, BaseRating::class);

        /** @var HasMany<BaseRating, TModel> $query */
        $query = $this->hasMany($related, 'related_type', 'post_type')
            ->selectRaw(
                'ratings.*,
                count(value) as rating_count,
                avg(value) as rating_avg,
                sum(if(user_id = ?, value, 0)) AS rating_my',
                [$userId]
            )->leftJoin(
                'rating_morph',
                function (JoinClause $join): void {
                    $join->on('rating_morph.rating_id', 'ratings.id')
                        ->whereColumn('rating_morph.post_type', 'ratings.related_type')
                        ->where('rating_morph.post_id', $this->getKey());
                }
            )->groupBy('ratings.id')
            ->with('post');

        return $query;
    }

    /**
     * @param Builder<TModel> $query
     *
     * @return Builder<TModel>
     */
    public function scopeWithRating(Builder $query): Builder
    {
        return $query->leftJoin(
            'rating_morph',
            function (JoinClause $join): void {
                $join->on('rating_morph.post_type', '=', 'ratings.related_type');
            }
        );
    }

    /**
     * @return MorphToMany<BaseRating, TModel, MorphPivot, 'pivot'>
     */
    public function myRatings(): MorphToMany
    {
        $userId = Auth::id();

        /** @var class-string<BaseRating> $related */
        $related = Rating::getClassName();
        Assert::subclassOf($related, BaseRating::class);

        /** @var MorphToMany<BaseRating, TModel, MorphPivot, 'pivot'> $query */
        $query = $this->morphToManyX($related, 'model')
            ->wherePivot('user_id', $userId);

        return $query;
    }

    /**
     * @return Collection<string|int, mixed>
     */
    public function getMyRatingAttribute(): Collection
    {
        /** @var Collection<int, BaseRating> $myRatings */
        $myRatings = $this->myRatings;

        return $myRatings->pluck('pivot.rating', 'post_id');
    }

    public function getRatingsAvgAttribute(?float $value): ?float
    {
        return (float) ($value ?? 0);
    }

    public function getRatingsCountAttribute(?int $value): ?int
    {
        return $value ?? 0;
    }

    /**
     * @param array<string, mixed> $filters
     *
     * @return Collection<int, BaseRating>
     */
    public function getRatingsWhere(array $filters): Collection
    {
        $query = $this->ratings();

        foreach ($filters as $key => $filterValue) {
            $query->where("extra_attributes->{$key}", $filterValue);
        }

        /** @var Collection<int, BaseRating> $result */
        $result = $query->get();

        return $result;
    }

    /**
     * Sync pivot verso rating che matchano extra_attributes.
     *
     * @param array<string, mixed> $where
     *
     * @return Collection<int, BaseRating>
     */
    public function syncRatingsWhere(array $where): Collection
    {
        /** @var class-string<BaseRating> $ratingClass */
        $ratingClass = Rating::getClassName();
        Assert::subclassOf($ratingClass, BaseRating::class);

        $ratings = $ratingClass::withExtraAttributes($where)->get();
        /*
        dddx([
            'ratings' => $ratings,
            'where' => $where,
        ]);
        */
        /** @var list<int|string> $ratingIds */
        $ratingIds = $ratings->pluck('id')->all();

        if ([] !== $ratingIds) {
            $this->ratings()->sync($ratingIds);
        }

        /** @var Collection<int, BaseRating> $result */
        $result = $this->ratings;

        return $result;
    }

    /**
     * @throws FileNotFoundException
     * @throws \ReflectionException
     */
    public function ratingAvgHtml(): string
    {
        $safeStringCastAction = app(SafeStringCastAction::class);
        $pivotAvg = $safeStringCastAction->execute($this->ratings_avg ?? 0);
        $pivotCount = $safeStringCastAction->execute($this->ratings_count ?? 0);
        $title = 'Vota '.$safeStringCastAction->execute($this->title ?? '');

        $msg = '<div class="rateit" data-rateit-value="'.$pivotAvg.'" data-rateit-ispreset="true" data-rateit-readonly="true"></div>';
        $msg .= '('.$pivotAvg.') '.$pivotCount.' Votes ';

        $ratingUrl = '#';

        $btn = '<button type="button" class="btn btn-red btn-danger" data-toggle="modal" data-target="#vueModal" data-title="'.$title.'" data-href="'.$ratingUrl.'">
        <span class="font-white"><i class="fa fa-star"></i> Vota ! </span>
        </button>';

        $btnIframe = '<button type="button" class="btn btn-red btn-danger" data-toggle="modal" data-target="#vueIframeModal" data-title="'.$title.'" data-href="'.$ratingUrl.'">
        <span class="font-white"><i class="fa fa-star"></i> Vota ! </span>
        </button>';

        return $msg.$btn.$btnIframe;
    }

    /**
     * Chiave sentinella dell'opzione "altro" nel Select di un rating con figli.
     *
     * Stringa non vuota `'other'` — **non** `''`. Era `''` fino al 2026-09-16: bug reale
     * segnalato dall'utente (Textarea mai obbligatoria dopo aver scelto «altro»), causa
     * confermata leggendo il sorgente vendor, non ipotizzata:
     * `vendor/filament/support/resources/js/utilities/select.js` dichiara
     * `blank(value) { return value === null || value === undefined || value === '' || ... }`
     * e lo usa per decidere se mostrare il placeholder — il widget JS di Filament **non
     * distingue** lo stato "altro scelto" (`''`) da "nessuna scelta" (`null`), quindi lo
     * stato che arriva al server tramite Livewire collassa sempre su `null` e
     * {@see selectIsOther()} non ritorna mai `true`. Una stringa non vuota non passa mai
     * `blank()`, quindi resta distinguibile per tutto il ciclo JS→Livewire→PHP. Le chiavi
     * reali dei figli sono id interi, quindi nessuna stringa non vuota puo' mai collidere.
     * Decisione GitHub laraxot/module_rating_fila5#57 (design), #58 (implementazione),
     * #61 (review gate), issue di regressione per questo fix da aprire in fase di
     * documentazione. Nome inglese per l'identificatore e per il valore (coerente,
     * correzione utente 2026-09-16 sul codice sempre in inglese) — la label visibile
     * resta italiana via `trans('rating::fields.altro')` sotto, quello e' un valore di
     * dominio rivolto all'utente, non un identificatore.
     */
    private const string OTHER_OPTION_KEY = 'other';

    /**
     * True solo se lo stato del campo e' esattamente la sentinella "altro".
     *
     * Mai `blank()`: confonderebbe "altro" scelto con "nessuna scelta fatta" (`null`),
     * che devono restare due stati diversi del Select. Prende il valore gia' risolto
     * (non `Get`) cosi' resta testabile senza costruire un componente Filament vivo.
     */
    private static function selectIsOther(mixed $selectValue): bool
    {
        return self::OTHER_OPTION_KEY === $selectValue;
    }

    /**
     * Il nome del campo di form che corrisponde a una riga di `ratings`.
     *
     * Convenzione unica, condivisa fra chi costruisce lo schema, chi legge lo stato e chi
     * salva le pivot: se cambia, cambia in un posto solo. `$pivotColumn` resta `'value'`
     * per compatibilita: ogni chiamata esistente continua a puntare li; `'note'` e' la
     * sola altra colonna pivot che il trait genera oggi (vedi `buildRatingComponent()`).
     */
    public static function ratingFieldName(BaseRating $rating, string $pivotColumn = 'value'): string
    {
        return 'ratings.'.$rating->id.'.pivot.'.$pivotColumn;
    }

    /**
     * Testo etichetta form per un criterio: `txt` se presente, altrimenti `title`.
     *
     * Diverso da {@see BaseRating::getLabel()} (albero / solo `title`). Qui preferiamo
     * il testo lungo della scheda e togliamo HTML — convenzione condivisa da ogni host
     * che decora i campi (story 5.149). Il trait **non** chiama `->label()` Filament
     * (D-1): restituisce solo la stringa; l'host (o
     * {@see DecoratesRatingFormFields}) la applica.
     */
    public static function formFieldLabel(BaseRating $rating): string
    {
        return strip_tags((string) ($rating->txt ?? $rating->title));
    }

    /**
     * I criteri che diventano campi: tutti tranne le opzioni.
     *
     * Un criterio con `parent_id` e' una voce del `Select` del padre, non un campo suo.
     * Pubblico perche' chi somma deve escludere le stesse righe: vedi `getTot()` di
     * IndennitaResponsabilita. Due definizioni di «opzione» prima o poi divergono.
     *
     * @param EloquentCollection<int, BaseRating>|null $ratings se null usa `$this->ratings`
     *
     * @return Collection<int, BaseRating>
     */
    public function ratingFormFields(?EloquentCollection $ratings = null): Collection
    {
        return ($ratings ?? $this->ratings)
            ->unique('id')
            ->reject(static fn (BaseRating $row): bool => null !== $row->parent_id);
    }

    /**
     * Ricostruisce `ratings.{id}.pivot.{value,note}` da persistere nel form, per l'host
     * che chiama `$this->form->fill($data)`.
     *
     * Generico per costruzione: qualunque host che consuma {@see getRatingsFormSchema()}
     * deve ri-idratare lo stesso stato, incluso il remap dell'opzione "altro" — `value`
     * null + `note` valorizzata vuol dire che l'utente aveva scelto «altro» all'ultimo
     * salvataggio, quindi il Select deve ripartire su {@see OTHER_OPTION_KEY}, non su
     * `null` ("non ancora risposto"). Se questo remap vivesse in ogni host lo
     * riscriverebbe uguale o lo dimenticherebbe — stesso motivo per cui
     * `ratingFieldName()`/`OTHER_OPTION_KEY` vivono qui e non nell'host. Nato dal
     * refactor 2026-09-16: prima duplicato (parziale, solo `value`) dentro
     * `CompilaIndennitaResponsabilita::fillFormWithInitialData()`.
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function hydrateRatingsFormData(array $data): array
    {
        /** @var array<string, array{pivot: array{value: mixed, note: mixed}}> $ratingsData */
        $ratingsData = [];

        foreach ($this->ratings as $rating) {
            $id = (string) $rating->id;
            $value = $rating->pivot->value;
            $note = $rating->pivot->note;

            if (null === $value && filled($note)) {
                $value = self::OTHER_OPTION_KEY;
            }

            $ratingsData[$id]['pivot']['value'] = $value;
            $ratingsData[$id]['pivot']['note'] = $note;
        }

        $data['ratings'] = $ratingsData;

        return $data;
    }

    /**
     * Scrive `ratings.{id}.pivot.{value,note}` del form sul pivot, per l'host dentro
     * `save()`.
     *
     * Generico per lo stesso motivo di {@see hydrateRatingsFormData()}: `''` (opzione
     * «altro») e `null` (non risposto) vanno **entrambi** persistiti come `null` sulla
     * colonna numerica `value` — mai cast a `0`, che li renderebbe indistinguibili da un
     * voto reale zero e romperebbe `HasRatingValuesFilter` (D-8, story Rating/5.141).
     * Le altre chiavi pivot presenti nello stato del form (es. `note`) passano invariate.
     * Nato dal refactor 2026-09-16: prima duplicato (con cast a `0`, il bug che questo
     * metodo corregge) dentro `CompilaIndennitaResponsabilita::save()`.
     *
     * @param array<int|string, array{pivot?: array<string, mixed>}> $ratingsData
     */
    public function syncRatingsFormData(array $ratingsData): void
    {
        foreach ($ratingsData as $id => $rating) {
            $pivot = $rating['pivot'] ?? [];
            $value = $pivot['value'] ?? null;

            $pivot['value'] = (self::OTHER_OPTION_KEY === $value || null === $value)
                ? null
                : (is_numeric($value) ? $value : null);

            $this->ratings()->updateExistingPivot($id, $pivot);
        }
    }

    /**
     * @param EloquentCollection<int, BaseRating>|null $ratings se null usa `$this->ratings`
     *
     * @return array<string, Component> indicizzato per nome di campo
     */
    public function getRatingsFormSchema(?RatingsFormCallerContract $caller = null, ?EloquentCollection $ratings = null): array
    {
        /** @var EloquentCollection<int, BaseRating> $rows */
        $rows = $ratings ?? $this->ratings;

        // Una query sola per tutti i figli: dentro il ciclo sarebbe una per criterio.
        $rows->loadMissing('children');

        $fields = $this->ratingFormFields($rows);

        /** @var Collection<int, BaseRating> $readonlyRatings */
        $readonlyRatings = $fields->where('is_readonly', true);

        $schema = [];
        foreach ($fields as $rating) {
            $component = $this->buildRatingComponent($rating, $caller, $readonlyRatings);

            $schema[self::ratingFieldName($rating)] = $caller?->decorateRatingField($rating, $component) ?? $component;
        }

        return $schema;
    }

    /**
     * Il campo di una riga, prima della decorazione dell'host.
     *
     * Sola lettura: `TextEntry`. Modificabile: `Select` se il criterio elenca dei figli,
     * altrimenti `TextInput`. Cambia solo il costruttore — regola di validazione presa dal
     * dato e gancio di ricalcolo sono in coda, scritti una volta sola: quando il gancio
     * viveva dentro il ramo del `TextInput`, il `Select` aggiunto dopo e' nato muto.
     *
     * @param Collection<int, BaseRating> $readonlyRatings
     */
    private function buildRatingComponent(
        BaseRating $rating,
        ?RatingsFormCallerContract $caller,
        Collection $readonlyRatings,
    ): Component {
        $field = self::ratingFieldName($rating);

        if (true === $rating->is_readonly) {
            return TextEntry::make($field)->inlineLabel();
        }

        // `getLabel()` e non `title`: e' il model a dire come si chiama, e restituisce
        // sempre una stringa — `pluck('title')` ne restituirebbe anche di nulle.
        $options = $rating->children
            ->mapWithKeys(static fn (BaseRating $child): array => [$child->id => $child->getLabel()])
            ->all();

        $afterStateUpdated = static function (Set $set, Get $get) use ($caller, $readonlyRatings): void {
            $caller?->recalculateRatingFields($set, $get, $readonlyRatings);
        };

        // Messaggi errore: senza validationAttribute Filament stampa lo state path
        // («ratings.52.pivot.value»). API distinta da label() → non viola D-1 (5.151).
        $humanName = self::formFieldLabel($rating);

        if ([] === $options) {
            return TextInput::make($field)
                ->numeric()
                ->live(onBlur: true)
                ->nullable()
                ->inlineLabel()
                ->validationAttribute($humanName)
                ->rules((string) ($rating->rule->value ?? ''))
                ->afterStateUpdated($afterStateUpdated);
        }

        // "Altro" in coda alle opzioni reali: l'utente vede prima i figli, l'eccezione per ultima.
        $options[self::OTHER_OPTION_KEY] = trans('rating::fields.altro');

        // ->required() esplicito, non lasciato a ->rules($rating->rule->value): nessun
        // caso di RuleEnum contiene "required" (verificato leggendo l'enum) — un rating
        // con RuleEnum::Null o un rule futuro senza quella parola avrebbe reso il Select
        // scegliibile-o-no senza vincolo. Istruzione diretta dell'utente 2026-09-16.
        // Niente ->nullable(): in tensione con ->required() (nullable ammette il vuoto,
        // required lo vieta) — rimosso invece di farli convivere.
        //
        // Validazione chiavi: RuleEnum (numeric|min|max) vale per TextInput senza figli.
        // Su Select con figli la sentinella OTHER_OPTION_KEY ('other') NON è numerica —
        // applicarla qui faceva fallire il Select prima che la note potesse risultare
        // obbligatoria (bug utente 2026-09-16 / story 5.152). Chiavi stringate: Livewire
        // invia stringhe e Rule::in confronta in strict mode.
        $allowedSelectValues = array_map(
            static fn (int|string $key): string => (string) $key,
            array_keys($options),
        );

        $select = Select::make($field)
            ->options($options)
            ->hiddenLabel()
            ->validationAttribute($humanName)
            ->placeholder(trans('rating::fields.scegli'))
            ->live()
            ->required()
            ->rules([Rule::in($allowedSelectValues)])
            ->afterStateUpdated($afterStateUpdated);

        // SEMPRE visibile accanto al Select, mai nascosta. Required solo su «altro».
        // $get($select) passa il Component: Get risolve lo statePath reale (incl. `data.`
        // del form). $get($field, isAbsolute: true) toglieva il prefisso `data.` e
        // selectIsOther vedeva sempre null → note mai obbligatoria (bug utente 2026-09-16).
        $note = Textarea::make(self::ratingFieldName($rating, 'note'))
            ->rows(3)
            ->hiddenLabel()
            ->validationAttribute(trans('rating::fields.note_for', ['label' => $humanName]))
            ->required(static fn (Get $get): bool => self::selectIsOther($get($select)));

        // Fieldset Filament 5: label + bordo + columns(2) di default (setUp).
        // https://filamentphp.com/docs/5.x/schemas/layouts#fieldset-component
        // columnSpan(2): nel form parent (tipicamente columns(2)) il blocco Select|Textarea
        // occupa entrambe le colonne. Host: decorateRatingField → label() (D-1).
        // markAsRequired: Select ha hiddenLabel(), l'asterisco sul legend segnala obbligo.
        return Fieldset::make()
            ->columnSpan(2)
            ->markAsRequired()
            ->schema([$select, $note]);
    }

    /**
     * @return array<string, string>
     */
    public function getRatingsRules(string $prefix, string $postfix): array
    {
        $safeStringCastAction = app(SafeStringCastAction::class);
        /** @var Collection<int, BaseRating> $rows */
        $rows = $this->ratings;
        $res = [];

        foreach ($rows as $row) {
            $keyWithPostfix = $prefix.$safeStringCastAction->execute($row->id).$postfix;
            $ruleStr = $this->ratingRuleToString($row->rule, $safeStringCastAction);

            if (Str::contains($ruleStr, ['numeric', 'integer']) && ! Str::contains($ruleStr, 'nullable')) {
                $ruleStr = 'nullable|'.$ruleStr;
            }

            $res[$keyWithPostfix] = $ruleStr;
        }

        return $res;
    }

    private function ratingRuleToString(mixed $rule, SafeStringCastAction $safeStringCastAction): string
    {
        if ($rule instanceof \BackedEnum) {
            return $safeStringCastAction->execute($rule->value);
        }

        return $safeStringCastAction->execute($rule);
    }

    /**
     * @return array<string, string>
     */
    public function getRatingsValidationAttributes(string $prefix, string $postfix): array
    {
        $safeStringCastAction = app(SafeStringCastAction::class);
        /** @var Collection<int, BaseRating> $rows */
        $rows = $this->ratings;
        $res = [];

        foreach ($rows as $row) {
            $keyWithPostfix = $prefix.$safeStringCastAction->execute($row->id).$postfix;
            $res[$keyWithPostfix] = $row instanceof BaseRating
                ? self::formFieldLabel($row)
                : $safeStringCastAction->execute($row->title ?? '');
        }

        return $res;
    }
}
