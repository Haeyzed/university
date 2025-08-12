<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class BatchResource extends JsonResource
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
             * The unique identifier for the batch.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the batch.
             * @var string $title
             * @example "Fall 2024"
             */
            'title' => $this->title,

            /**
             * The start date of the batch.
             * @var string $start_date
             * @example "2024-09-01"
             */
            'start_date' => $this->start_date,

            /**
             * The end date of the batch.
             * @var string $end_date
             * @example "2025-05-31"
             */
            'end_date' => $this->end_date,

            /**
             * The status of the batch (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the batch.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the batch.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the batch.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the batch.
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
             * The programs associated with this batch.
             * @var \App\Http\Resources\ProgramResource[] $programs
             */
            'programs' => ProgramResource::collection($this->whenLoaded('programs')),
        ];
    }
}
