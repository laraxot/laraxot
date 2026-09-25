<?php

declare(strict_types=1);

namespace Modules\UI\Filament\Forms\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Filament\Forms\Components\XotBaseSelect;

class SelectState extends XotBaseSelect
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->options(fn (?Model $record): array => $this->resolveStateOptions($record));
        $this->required();
    }

    /**
     * @return array<int|string, string>
     */
    private function resolveStateOptions(?Model $record): array
    {
        if (null === $record) {
            return $this->resolveDefaultStateOptions();
        }

        if (! method_exists($record, 'getStatesFor')) {
            return [];
        }

        $statesCollection = $record->getStatesFor($this->getName());
        $statesRaw = \is_object($statesCollection) && method_exists($statesCollection, 'toArray')
            ? $statesCollection->toArray()
            : [];
        /** @var array<int|string, mixed> $states */
        $states = $statesRaw;

        return $this->combineStateOptions($states);
    }

    /**
     * @return array<int|string, string>
     */
    private function resolveDefaultStateOptions(): array
    {
        $name = $this->getName();
        $model = $this->getModel();
        if (! \is_string($model) || ! class_exists($model)) {
            return [];
        }

        $instance = app($model);
        if (! \is_object($instance) || ! method_exists($instance, 'getDefaultStateFor')) {
            return [];
        }

        $statesRaw = $instance->getDefaultStateFor($name);
        if (! \is_array($statesRaw)) {
            $statesRaw = Arr::wrap($statesRaw);
        }

        /** @var array<int|string, mixed> $statesRaw */
        return $this->combineStateOptions($statesRaw);
    }

    /**
     * @param array<int|string, mixed> $states
     *
     * @return array<int|string, string>
     */
    private function combineStateOptions(array $states): array
    {
        $statesKeys = array_map(
            static fn ($key) => SafeStringCastAction::cast($key),
            array_keys($states),
        );
        $statesValues = array_map(
            static fn ($value) => SafeStringCastAction::cast($value),
            array_values($states),
        );
        $combined = array_combine($statesKeys, $statesValues);

        return $combined ? $combined : [];
    }
}
