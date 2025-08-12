<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class VisitorResource extends JsonResource
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
             * The unique identifier for the visitor.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the visit purpose.
             * @var int $visit_purpose_id
             * @example 1
             */
            'visit_purpose_id' => $this->visit_purpose_id,

            /**
             * The title of the visit purpose.
             * @var string|null $visit_purpose_title
             * @example "Meeting with Faculty"
             */
            'visit_purpose_title' => $this->whenLoaded('visitPurpose', fn() => $this->visitPurpose->title),

            /**
             * The name of the visitor.
             * @var string $name
             * @example "Michael Brown"
             */
            'name' => $this->name,

            /**
             * The phone number of the visitor.
             * @var string|null $phone
             * @example "+15551234567"
             */
            'phone' => $this->phone,

            /**
             * The email address of the visitor.
             * @var string|null $email
             * @example "michael.b@example.com"
             */
            'email' => $this->email,

            /**
             * The address of the visitor.
             * @var string|null $address
             * @example "789 Pine St, City"
             */
            'address' => $this->address,

            /**
             * The check-in time of the visit.
             * @var string $check_in
             * @example "10:00:00"
             */
            'check_in' => $this->check_in,

            /**
             * The check-out time of the visit.
             * @var string|null $check_out
             * @example "11:30:00"
             */
            'check_out' => $this->check_out,

            /**
             * The date of the visit.
             * @var string $date
             * @example "2024-07-19"
             */
            'date' => $this->date,

            /**
             * The status of the visitor record (true for active, false for inactive).
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
