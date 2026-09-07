<?php

/**
 * Definizione dell'interfaccia per i modelli dell'applicazione.
 */

declare(strict_types=1);

namespace Modules\User\Contracts;

<<<<<<< HEAD
<<<<<<< HEAD
/*
 * Interfaccia ModelContract deprecata.
 *
 * Questa interfaccia causava conflitti con Illuminate\Database\Eloquent\Model
 * ed è stata rimossa per risolvere problemi di compatibilità.
 *
 * Utilizzare direttamente i metodi di Eloquent Model invece di questa interfaccia.
 */
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Model;

/**
 * Interfaccia ModelContract che deve essere implementata dai modelli.
 *
 * @phpstan-require-extends Model
 *
 * @mixin \Eloquent
 */
interface ModelContract
{
    /**
     * Duplicate the instance and unset all the loaded relations.
     *
     * @return static The model instance without relations
     */
    public function withoutRelations();

    /**
     * Fill the model with an array of attributes. Force mass assignment.
     *
     * @param array<string, mixed> $attributes Gli attributi da assegnare al modello
     * @return static Il modello stesso
     */
    public function forceFill(array $attributes);

    /**
     * Save the model to the database.
     *
     * @param array<string, mixed> $options Opzioni per il salvataggio
     * @return bool True se il salvataggio è avvenuto con successo, false altrimenti
     */
    public function save(array $options = []);

    /*
     * Save a new model and return the instance. Allow mass-assignment.
     *
     * @param array<string, mixed> $attributes Gli attributi da assegnare al modello
     * @return static Il nuovo modello creato
     *
     * public function forceCreate(array $attributes);
     */

    /**
     * Convert the model instance to an array.
     *
     * @return array<string, mixed> Il modello convertito in array
     */
    public function toArray();

    /**
     * Get the value of the model's primary key.
     *
     * @return string|int|null Il valore della chiave primaria
     */
    public function getKey();

    /*
     * Add a basic where clause to the query.
     *
     * @param  \Closure|string|array|\Illuminate\Contracts\Database\Query\Expression  $column
     * @param  mixed  $operator
     * @param  mixed  $value
     * @param  string $boolean
     * @return static
     *
     * public function where($column, $operator = null, $value = null, $boolean = 'and');
     */
    /*
     * Execute the query and get the first result or throw an exception.
     *
     * @param  array|string $columns
     * @return \Illuminate\Database\Eloquent\Model|static
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     *
     * public function firstOrFail($columns = ['*']);
     */
}
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
/*
 * Interfaccia ModelContract deprecata.
 *
 * Questa interfaccia causava conflitti con Illuminate\Database\Eloquent\Model
 * ed è stata rimossa per risolvere problemi di compatibilità.
 *
 * Utilizzare direttamente i metodi di Eloquent Model invece di questa interfaccia.
 */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
