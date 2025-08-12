<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class NoticeResource extends JsonResource
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
             * The unique identifier for the notice.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the notice category.
             * @var int $notice_category_id
             * @example 1
             */
            'notice_category_id' => $this->notice_category_id,

            /**
             * The title of the notice category.
             * @var string|null $notice_category_title
             * @example "General Announcements"
             */
            'notice_category_title' => $this->whenLoaded('noticeCategory', fn() => $this->noticeCategory->title),

            /**
             * The title of the notice.
             * @var string $title
             * @example "Holiday Schedule"
             */
            'title' => $this->title,

            /**
             * The description or content of the notice.
             * @var string $description
             * @example "The university will be closed on August 15th for Independence Day."
             */
            'description' => $this->description,

            /**
             * The date the notice was published.
             * @var string $date
             * @example "2024-07-10"
             */
            'date' => $this->date,

            /**
             * The status of the notice (true for active, false for inactive).
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
