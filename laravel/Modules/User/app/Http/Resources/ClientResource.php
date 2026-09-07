<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Models\OauthClient as Client;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\User;

/**
 * @property User|null $owner
=======

/**
 * @property \Modules\User\Models\User|null $owner
>>>>>>> 60a2c9a9 (.)
=======

/**
 * @property \Modules\User\Models\User|null $owner
=======
use Modules\User\Models\User;

/**
 * @property User|null $owner
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 *
 * @mixin Client
 */
final class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
<<<<<<< HEAD
<<<<<<< HEAD
        /** @var Client $client */
        $client = $this->resource;

        return [
            'id' => $client->id,
            'name' => $client->name,
            'owner' => $this->when(
                null !== $client->owner,
                fn (): OwnerResource => new OwnerResource($client->owner)
=======
=======
>>>>>>> 87273113 (.)
        return [
            'id' => $this->id,
            'name' => $this->name,
            'owner' => $this->when(
                isset($this->owner),
                fn (): OwnerResource => new OwnerResource($this->owner)
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
        /** @var Client $client */
        $client = $this->resource;

        return [
            'id' => $client->id,
            'name' => $client->name,
            'owner' => $this->when(
                null !== $client->owner,
                fn (): OwnerResource => new OwnerResource($client->owner)
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            ),
        ];
    }
}
