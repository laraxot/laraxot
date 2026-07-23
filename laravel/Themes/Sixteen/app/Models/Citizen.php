<?php

declare(strict_types=1);

namespace Themes\Sixteen\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Anagrafica cittadino per prenotazioni (tema Sixteen).
 *
 * @property int $id
 * @property string|null $fiscal_code
 * @property string|null $first_name
 * @property string|null $last_name
 */
class Citizen extends Model
{
    protected $table = 'sixteen_citizens';

    /** @var list<string> */
    protected $fillable = [
        'fiscal_code',
        'first_name',
        'last_name',
    ];
}
