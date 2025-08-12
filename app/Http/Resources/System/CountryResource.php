<?php

namespace App\Http\Resources\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class CountryResource
 *
 * @property int $id The unique identifier for the country.
 * @property string $iso2 The ISO2 code of the country.
 * @property string $name The name of the country.
 * @property bool $status The status of the country.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 */
class CountryResource extends JsonResource
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
             * The unique identifier for the country.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ISO2 code of the country.
             * @var string $iso2
             * @example "US"
             */
            'iso2' => $this->iso2,

            /**
             * The name of the country.
             * @var string $name
             * @example "United States"
             */
            'name' => $this->name,

            /**
             * The status of the country.
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

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
