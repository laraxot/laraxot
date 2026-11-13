<?php

declare(strict_types=1);

namespace Modules\Xot\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\ValueObjects\PhoneValueObject;

/**
 * @implements CastsAttributes<PhoneValueObject, PhoneValueObject>
 */
class PhoneCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
<<<<<<< HEAD
=======
<<<<<<< .merge_file_nDuTIg
>>>>>>> 77b1e2748 (first)
     * @param  Model  $_model  The Eloquent model instance
     * @param  string  $_key  The attribute key
     * @param  mixed  $value  The raw value from database
     * @param  array<string, mixed>  $_attributes  All model attributes
<<<<<<< HEAD
=======
=======
     * <<<<<<< HEAD
     *
     * @param Model                $_model      The Eloquent model instance
     * @param string               $_key        The attribute key
     * @param mixed                $value       The raw value from database
     * @param array<string, mixed> $_attributes All model attributes
     *                                          =======
     *                                          <<<<<<< HEAD
     *                                          =======
     *                                          <<<<<<< HEAD
     *                                          <<<<<<< HEAD
     *
     * >>>>>>> laraxot/dev
     * @param Model                $_model      The Eloquent model instance
     * @param string               $_key        The attribute key
     * @param mixed                $value       The raw value from database
     * @param array<string, mixed> $_attributes All model attributes
     *                                          <<<<<<< HEAD
     *                                          =======
     *                                          =======
     * @param Model                $_model      The Eloquent model instance
     * @param string               $_key        The attribute key
     * @param mixed                $value       The raw value from database
     * @param array<string, mixed> $_attributes All model attributes
     *                                          >>>>>>> laraxot/dev
     *                                          =======
     * @param Model                $_model      The Eloquent model instance
     * @param string               $_key        The attribute key
     * @param mixed                $value       The raw value from database
     * @param array<string, mixed> $_attributes All model attributes
     *                                          >>>>>>> laraxot/dev
     *                                          >>>>>>> laraxot/dev
     *                                          >>>>>>> laraxot/dev
>>>>>>> .merge_file_1O9dwP
>>>>>>> 77b1e2748 (first)
     */
    public function get(Model $_model, string $_key, mixed $value, array $_attributes): PhoneValueObject
    {
        if (! is_string($value)) {
            throw new \Exception('['.__LINE__.']['.class_basename($this).']');
        }

        return PhoneValueObject::fromString($value);
    }

    /**
     * Prepare the given value for storage.
     *
<<<<<<< HEAD
=======
<<<<<<< .merge_file_nDuTIg
>>>>>>> 77b1e2748 (first)
     * @param  Model  $_model  The Eloquent model instance
     * @param  string  $_key  The attribute key
     * @param  mixed  $value  The value to be stored
     * @param  array<string, mixed>  $_attributes  All model attributes
<<<<<<< HEAD
=======
=======
     * <<<<<<< HEAD
     *
     * @param Model                $_model      The Eloquent model instance
     * @param string               $_key        The attribute key
     * @param mixed                $value       The value to be stored
     * @param array<string, mixed> $_attributes All model attributes
     *                                          =======
     *                                          <<<<<<< HEAD
     *                                          =======
     *                                          <<<<<<< HEAD
     *                                          <<<<<<< HEAD
     *
     * >>>>>>> laraxot/dev
     * @param Model                $_model      The Eloquent model instance
     * @param string               $_key        The attribute key
     * @param mixed                $value       The value to be stored
     * @param array<string, mixed> $_attributes All model attributes
     *                                          <<<<<<< HEAD
     *                                          =======
     *                                          =======
     * @param Model                $_model      The Eloquent model instance
     * @param string               $_key        The attribute key
     * @param mixed                $value       The value to be stored
     * @param array<string, mixed> $_attributes All model attributes
     *                                          >>>>>>> laraxot/dev
     *                                          =======
     * @param Model                $_model      The Eloquent model instance
     * @param string               $_key        The attribute key
     * @param mixed                $value       The value to be stored
     * @param array<string, mixed> $_attributes All model attributes
     *                                          >>>>>>> laraxot/dev
     *                                          >>>>>>> laraxot/dev
     *                                          >>>>>>> laraxot/dev
>>>>>>> .merge_file_1O9dwP
>>>>>>> 77b1e2748 (first)
     */
    public function set(Model $_model, string $_key, mixed $value, array $_attributes): string
    {
        if (! $value instanceof PhoneValueObject) {
            throw new \InvalidArgumentException('The given value is not an Phone instance.');
        }

        return $value->toString();
    }
}
