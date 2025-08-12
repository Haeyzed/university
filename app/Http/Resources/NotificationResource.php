<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class NotificationResource extends JsonResource
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
             * The unique identifier for the notification.
             * @var string $id
             * @example "a1b2c3d4-e5f6-7890-1234-567890abcdef"
             */
            'id' => $this->id,

            /**
             * The type of notification.
             * @var string $type
             * @example "App\Notifications\NewAssignment"
             */
            'type' => $this->type,

            /**
             * The ID of the notifiable entity (e.g., user ID).
             * @var int $notifiable_id
             * @example 101
             */
            'notifiable_id' => $this->notifiable_id,

            /**
             * The type of the notifiable entity (e.g., "App\Models\User").
             * @var string $notifiable_type
             * @example "App\Models\User"
             */
            'notifiable_type' => $this->notifiable_type,

            /**
             * The JSON data associated with the notification.
             * @var array<string, mixed> $data
             * @example {"assignment_id": 5, "title": "New Homework Posted"}
             */
            'data' => $this->data,

            /**
             * The timestamp when the notification was read.
             * @var string|null $read_at
             * @example "2024-07-19 12:15:00"
             */
            'read_at' => $this->read_at ? Carbon::parse($this->read_at)->format('Y-m-d H:i:s') : null,

            /**
             * The timestamp when the notification was created.
             * @var string $created_at
             * @example "2024-07-19 12:00:00"
             */
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),

            /**
             * The timestamp when the notification was last updated.
             * @var string $updated_at
             * @example "2024-07-19 12:00:00"
             */
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
