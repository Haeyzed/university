<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TransportVehicleResource extends JsonResource
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
             * The unique identifier for the transport vehicle.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The name or identifier of the vehicle.
             * @var string $name
             * @example "Bus 1"
             */
            'name' => $this->name,

            /**
             * The vehicle number.
             * @var string $vehicle_no
             * @example "ABC-123"
             */
            'vehicle_no' => $this->vehicle_no,

            /**
             * The model of the vehicle.
             * @var string|null $model
             * @example "Mercedes-Benz Sprinter"
             */
            'model' => $this->model,

            /**
             * The year of manufacture.
             * @var string|null $year
             * @example "2020"
             */
            'year' => $this->year,

            /**
             * The capacity of the vehicle (number of seats).
             * @var int|null $capacity
             * @example 30
             */
            'capacity' => $this->capacity,

            /**
             * The driver's name.
             * @var string|null $driver_name
             * @example "John Driver"
             */
            'driver_name' => $this->driver_name,

            /**
             * The driver's license number.
             * @var string|null $driver_license
             * @example "DL-987654"
             */
            'driver_license' => $this->driver_license,

            /**
             * The driver's phone number.
             * @var string|null $driver_phone
             * @example "+1234567890"
             */
            'driver_phone' => $this->driver_phone,

            /**
             * The status of the transport vehicle (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

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
        ];
    }
}
