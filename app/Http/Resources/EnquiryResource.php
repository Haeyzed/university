<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class EnquiryResource extends JsonResource
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
             * The unique identifier for the enquiry.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the enquiry source.
             * @var int $enquiry_source_id
             * @example 1
             */
            'enquiry_source_id' => $this->enquiry_source_id,

            /**
             * The title of the enquiry source.
             * @var string|null $enquiry_source_title
             * @example "Website"
             */
            'enquiry_source_title' => $this->whenLoaded('enquirySource', fn() => $this->enquirySource->title),

            /**
             * The ID of the enquiry reference.
             * @var int $enquiry_reference_id
             * @example 1
             */
            'enquiry_reference_id' => $this->enquiry_reference_id,

            /**
             * The title of the enquiry reference.
             * @var string|null $enquiry_reference_title
             * @example "Friend"
             */
            'enquiry_reference_title' => $this->whenLoaded('enquiryReference', fn() => $this->enquiryReference->title),

            /**
             * The name of the person making the enquiry.
             * @var string $name
             * @example "Alice Johnson"
             */
            'name' => $this->name,

            /**
             * The phone number of the enquirer.
             * @var string|null $phone
             * @example "+1122334455"
             */
            'phone' => $this->phone,

            /**
             * The email address of the enquirer.
             * @var string|null $email
             * @example "alice.j@example.com"
             */
            'email' => $this->email,

            /**
             * The address of the enquirer.
             * @var string|null $address
             * @example "456 Oak Ave, City"
             */
            'address' => $this->address,

            /**
             * The description or details of the enquiry.
             * @var string $description
             * @example "Interested in the Computer Science program for Fall 2025."
             */
            'description' => $this->description,

            /**
             * The date of the enquiry.
             * @var string $date
             * @example "2024-07-19"
             */
            'date' => $this->date,

            /**
             * The follow-up date for the enquiry.
             * @var string|null $follow_up_date
             * @example "2024-07-26"
             */
            'follow_up_date' => $this->follow_up_date,

            /**
             * The status of the enquiry (e.g., 0 for pending, 1 for closed).
             * @var int $status
             * @example 0
             */
            'status' => $this->status,

            /**
             * The ID of the user who created the enquiry.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the enquiry.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the enquiry.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the enquiry.
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
