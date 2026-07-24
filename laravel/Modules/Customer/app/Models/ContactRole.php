<?php

declare(strict_types=1);

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 *
 * @method static \Modules\Customer\Database\Factories\ContactRoleFactory factory($count = null, $state = [])
 */
final class ContactRole extends BaseModel
{
    protected $guarded = [];

    /** @return HasMany<Contact, $this> */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
