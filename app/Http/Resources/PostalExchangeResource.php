<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PostalExchangeResource extends JsonResource
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
             * The unique identifier for the postal exchange.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the postal exchange type.
             * @var int $postal_exchange_type_id
             * @example 1
             */
            'postal_exchange_type_id' => $this->postal_exchange_type_id,

            /**
             * The title of the postal exchange type.
             * @var string|null $postal_exchange_type_title
             * @example "Incoming Mail"
             */
            'postal_exchange_type_title' => $this->whenLoaded('postalExchangeType', fn() => $this->postalExchangeType->title),

            /**
             * The title or subject of the postal item.
             * @var string $title
             * @example "Admissions Documents"
             */
            'title' => $this->title,

            /**
             * The reference number of the postal item.
             * @var string|null $reference_no
             * @example "REF-2024-001"
             */
            'reference_no' => $this->reference_no,

            /**
             * The date of the postal exchange.
             * @var string $date
             * @example "2024-07-18"
             */
            'date' => $this->date,

            /**
             * The from address/person.
             * @var string|null $from
             * @example "Applicant John Doe"
             */
            'from' => $this->from,

            /**
             * The to address/person.
             * @var string|null $to
             * @example "Admissions Office"
             */
            'to' => $this->to,

            /**
             * The description of the postal item.
             * @var string|null $description
             * @example "Application documents for Computer Science program."
             */
            'description' => $this->description,

            /**
             * The path to the postal item file.
             * @var string|null $file
             * @example "/uploads/postal/admissions_docs.pdf"
             */
            'file' => $this->file,

            /**
             * The status of the postal exchange (true for active, false for inactive).
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
