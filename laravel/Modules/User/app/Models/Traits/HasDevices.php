<?php

declare(strict_types=1);

namespace Modules\User\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\Pivot;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
use Illuminate\Database\Eloquent\Relations\Pivot;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Models\Device;

trait HasDevices
{
<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return BelongsToMany<Device, $this, Pivot>
     */
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
    /**
     * @return BelongsToMany<Device, $this, Pivot>
     */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function devices(): BelongsToMany
    {
        return $this->belongsToManyX(Device::class);
    }
}
