<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PhoneLogResource extends JsonResource
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
             * The unique identifier for the phone log.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The type of call (e.g., "incoming", "outgoing").
             * @var string $call_type
             * @example "incoming"
             */
            'call_type' => $this->call_type,

            /**
             * The phone number involved in the call.
             * @var string $phone_number
             * @example "+1234567890"
             */
            'phone_number' => $this->phone_number,

            /**
             * The name of the person associated with the phone number.
             * @var string|null $name
             * @example "John Doe"
             */
            'name' => $this->name,

            /**
             * The purpose or subject of the call.
             * @var string|null $subject
             * @example "Admission Inquiry"
             */
            'subject' => $this->subject,

            /**
             * The description or notes about the call.
             * @var string|null $description
             * @example "Applicant called to inquire about program requirements."
             */
            'description' => $this->description,

            /**
             * The date of the call.
             * @var string $date
             * @example "2024-07-19"
             */
            'date' => $this->date,

            /**
             * The status of the phone log (true for active, false for inactive).
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
