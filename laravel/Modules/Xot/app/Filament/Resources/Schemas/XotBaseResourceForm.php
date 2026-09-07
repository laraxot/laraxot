<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Schemas;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Xot\Filament\Traits\HasXotForm;
use Webmozart\Assert\Assert;

abstract class XotBaseResourceForm
{
    use HasXotForm;
    // private static ?self $_instance = null;

    final public static function configure(Schema $schema): Schema
    {
        if (static::class === self::class) {
            throw new \LogicException('XotBaseResourceForm::configure() must be called on a concrete form class.');
        }
        $instance = app(static::class);
        Assert::isInstanceOf($instance, self::class);

        // static::$_instance = $instance;
        // return static::$_instance->form($schema);
        return $instance->form($schema);
    }

    /**
     * @return array<string, Component>
     */
    abstract public function getFormSchema(): array;

    /**
     * Elenco degli step Wizard per form multi‑passaggio (nome ufficiale allineato a Filament **`HasWizard::getSteps()`**).
     * I form lineari lo lasciano vuoto.
     *
     * @return array<string, Step>
     */
    public static function getSteps(): array
    {
        return [];
    }

    /**
     * Costruisce il callback usato da `Select::getOptionLabelUsing()`.
     *
     * Regressione: una colonna titolo nulla o vuota faceva arrivare `null` a
     * `Filament\Forms\Components\Select::isOptionDisabled(string|Htmlable $label)`
     * mandando in TypeError l'intera pagina di edit. Se la colonna titolo e'
     * vuota si ripiega su `#{chiave primaria}`.
     *
     * @return \Closure(Model): string
     */
    protected static function optionLabelFromRecord(string $titleAttribute = 'name'): \Closure
    {
        return static function (Model $record) use ($titleAttribute): string {
            $title = $record->getAttribute($titleAttribute);

            if (\is_string($title) && $title !== '') {
                return $title;
            }

            $key = $record->getKey();

            return '#'.(\is_scalar($key) ? (string) $key : '');
        };
    }

    protected static function getStepByName(string $name): Step
    {
        $methodName = Str::of($name)
            ->snake()
            ->studly()
            ->prepend('get')
            ->append('Schema')
            ->toString();

        if (method_exists(static::class, $methodName)) {
            $schemaResult = static::$methodName();
            /** @var array<Htmlable|string> $schemaComponents */
            $schemaComponents = \is_array($schemaResult) ? array_values($schemaResult) : [];

            return Step::make($name)->schema($schemaComponents);
        }
        dddx($methodName);

        return Step::make($name)->schema([]);
    }
}
