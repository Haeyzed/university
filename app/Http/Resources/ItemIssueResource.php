<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ItemIssueResource extends JsonResource
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
             * The unique identifier for the item issue.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the associated item.
             * @var int $item_id
             * @example 1
             */
            'item_id' => $this->item_id,

            /**
             * The title of the associated item.
             * @var string|null $item_title
             * @example "Projector"
             */
            'item_title' => $this->whenLoaded('item', fn() => $this->item->title),

            /**
             * The ID of the user to whom the item is issued.
             * @var int $user_id
             * @example 101
             */
            'user_id' => $this->user_id,

            /**
             * The name of the user to whom the item is issued.
             * @var string|null $user_name
             * @example "Professor David"
             */
            'user_name' => $this->whenLoaded('user', fn() => $this->user->name),

            /**
             * The quantity of the item issued.
             * @var int $quantity
             * @example 1
             */
            'quantity' => $this->quantity,

            /**
             * The issue date of the item.
             * @var string $issue_date
             * @example "2024-07-10"
             */
            'issue_date' => $this->issue_date,

            /**
             * The return date of the item.
             * @var string|null $return_date
             * @example "2024-07-12"
             */
            'return_date' => $this->return_date,

            /**
             * The status of the item issue (e.g., 0 for issued, 1 for returned).
             * @var int $status
             * @example 0
             */
            'status' => $this->status,

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
