<?php

declare(strict_types=1);

namespace Modules\UI\Filament\Tables\Columns;

use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Modules\Xot\Actions\Cast\SafeStringCastAction;

class SelectStateColumn extends SelectColumn
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->options(fn (Model $record, mixed $state): array => $this->resolveColumnStateOptions($record, $state));

        $this->beforeStateUpdated(function (Model $record, mixed $stateRaw): void {
            $this->applyStateTransition($record, $stateRaw);
        });
    }

    private function applyStateTransition(Model $record, mixed $stateRaw): void
    {
        if (! \is_string($stateRaw)) {
            return;
        }

        $state = $stateRaw;
        $message = '';

        $recordState = $record->getAttribute('state');
        if (! \is_object($recordState)) {
            return;
        }

        if (! method_exists($recordState, 'transitionTo')) {
            return;
        }

        $recordState->transitionTo($state, $message);
    }

    /**
     * @return array<int|string, string>
     */
    private function resolveColumnStateOptions(Model $record, mixed $state): array
    {
        $name = $this->getName();
        if (null === $state) {
            return $this->resolveDefaultColumnStates($record, $name);
        }

        /** @var array<int|string, mixed> $states */
        $states = $this->resolveTransitionableStates($record, $state, $name);
        $states = $this->prependCurrentStateName($state, $states);

        $statesFiltered = array_filter($states, static function (mixed $item): bool {
            return \is_string($item) || \is_int($item);
        });

        /** @var array<int|string, mixed> $statesFiltered */
        return $this->combineStateOptions($statesFiltered);
    }

    /**
     * @return array<int|string, string>
     */
    private function resolveDefaultColumnStates(Model $record, string $name): array
    {
        if (! method_exists($record, 'getDefaultStateFor')) {
            return [];
        }

        $defaultStates = $record->getDefaultStateFor($name);
        $states = Arr::wrap($defaultStates);
        /** @var array<int|string, mixed> $states */
        $states = \is_array($states) ? $states : [];

        return $this->combineStateOptions($states);
    }

    /**
     * @return array<int|string, mixed>
     */
    private function resolveTransitionableStates(Model $record, mixed $state, string $name): array
    {
        if (! \is_object($state) || ! method_exists($state, 'transitionableStates')) {
            return $this->fetchFallbackStates($record, $name);
        }

        try {
            $transitionableStates = $state->transitionableStates();
            if (! is_iterable($transitionableStates)) {
                return [];
            }

            return \is_array($transitionableStates) ? $transitionableStates : iterator_to_array($transitionableStates);
        } catch (\Exception) {
            return $this->fetchFallbackStates($record, $name);
        }
    }

    /**
     * @return array<int|string, mixed>
     */
    private function fetchFallbackStates(Model $record, string $name): array
    {
        if (! method_exists($record, 'getStatesFor')) {
            return [];
        }

        $fetchedStates = $record->getStatesFor($name);
        if (! \is_object($fetchedStates) || ! method_exists($fetchedStates, 'toArray')) {
            return [];
        }

        $statesArray = $fetchedStates->toArray();

        return \is_array($statesArray) ? $statesArray : [];
    }

    /**
     * @param  array<int|string, mixed>  $states
     * @return array<int|string, mixed>
     */
    private function prependCurrentStateName(mixed $state, array $states): array
    {
        if (! \is_object($state)) {
            return $states;
        }

        $stateNameProperty = $this->resolveStaticStateName($state::class);
        if (null === $stateNameProperty) {
            return $states;
        }

        $statesValues = array_values($states);
        /** @var list<int|string> $statesValuesTyped */
        $statesValuesTyped = $statesValues;

        return [$stateNameProperty, ...$statesValuesTyped];
    }

    private function resolveStaticStateName(string $stateClass): ?string
    {
        if (! class_exists($stateClass)) {
            return null;
        }

        try {
            $reflection = new \ReflectionClass($stateClass);
            if (! $reflection->hasProperty('name')) {
                return null;
            }

            $nameProperty = $reflection->getStaticPropertyValue('name');

            return \is_string($nameProperty) ? $nameProperty : null;
        } catch (\ReflectionException) {
            return null;
        }
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
