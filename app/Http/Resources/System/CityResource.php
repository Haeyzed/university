<?php

namespace App\Http\Resources\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class CityResource
 *
 * @property int $id The unique identifier for the city.
 * @property int $country_id The ID of the country the city belongs to.
 * @property int|null $state_id The ID of the state the city belongs to.
 * @property string $name The name of the city.
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 *
 * @property-read CountryResource $country
 * @property-read StateResource|null $state
 */
class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /**
             * The unique identifier for the city.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the country the city belongs to.
             * @var int $country_id
             * @example 1
             */
            'country_id' => $this->country_id,

            /**
             * The ID of the state the city belongs to.
             * @var int|null $state_id
             * @example 1
             */
            'state_id' => $this->state_id,

            /**
             * The name of the city.
             * @var string $name
             * @example "Los Angeles"
             */
            'name' => $this->name,

            /**
             * The associated country resource.
             * @var CountryResource $country
             */
            'country' => CountryResource::make($this->whenLoaded('country')),

            /**
             * The associated state resource.
             * @var StateResource|null $state
             */
            'state' => StateResource::make($this->whenLoaded('state')),

            /**
             * The ID of the user who created the record.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the record.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the record.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the record.
             * @var string|null $updated_by_name
             * @example "Admin User"
             */
            'updated_by_name' => $this->whenLoaded('updatedBy', fn() => $this->updatedBy->name),

            /**
             * The timestamp when the record was created.
             * @var string $created_at
             * @example "2024-07-19 12:00:00"
             */
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),

            /**
             * The timestamp when the record was last updated.
             * @var string $updated_at
             * @example "2024-07-19 12:30:00"
             */
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),

            /**
             * The timestamp when the record was last deleted.
             * @var string|null $deleted_at
             * @example "2024-07-19 12:30:00"
             */
            'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null,
        ];
    }
}
