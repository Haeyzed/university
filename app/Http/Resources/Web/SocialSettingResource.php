<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class SocialSettingResource
 *
 * @property int $id The unique identifier for the social setting.
 * @property string|null $facebook The Facebook profile URL.
 * @property string|null $twitter The Twitter profile URL.
 * @property string|null $linkedin The LinkedIn profile URL.
 * @property string|null $instagram The Instagram profile URL.
 * @property string|null $pinterest The Pinterest profile URL.
 * @property string|null $youtube The YouTube channel URL.
 * @property string|null $tiktok The TikTok profile URL.
 * @property string|null $skype The Skype username.
 * @property string|null $telegram The Telegram username or link.
 * @property string|null $whatsapp The WhatsApp number or link.
 * @property bool $status The status of the social setting (true for active, false for inactive).
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 */
class SocialSettingResource extends JsonResource
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
             * The unique identifier for the social setting.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The Facebook profile URL.
             * @var string|null $facebook
             * @example "https://facebook.com/universityofexample"
             */
            'facebook' => $this->facebook,

            /**
             * The Twitter profile URL.
             * @var string|null $twitter
             * @example "https://twitter.com/universityofexample"
             */
            'twitter' => $this->twitter,

            /**
             * The LinkedIn profile URL.
             * @var string|null $linkedin
             * @example "https://linkedin.com/company/universityofexample"
             */
            'linkedin' => $this->linkedin,

            /**
             * The Instagram profile URL.
             * @var string|null $instagram
             * @example "https://instagram.com/universityofexample"
             */
            'instagram' => $this->instagram,

            /**
             * The Pinterest profile URL.
             * @var string|null $pinterest
             * @example "https://pinterest.com/universityofexample"
             */
            'pinterest' => $this->pinterest,

            /**
             * The YouTube channel URL.
             * @var string|null $youtube
             * @example "https://youtube.com/channel/universityofexample"
             */
            'youtube' => $this->youtube,

            /**
             * The TikTok profile URL.
             * @var string|null $tiktok
             * @example "https://tiktok.com/@universityofexample"
             */
            'tiktok' => $this->tiktok,

            /**
             * The Skype username.
             * @var string|null $skype
             * @example "live:universityofexample"
             */
            'skype' => $this->skype,

            /**
             * The Telegram username or link.
             * @var string|null $telegram
             * @example "https://t.me/universityofexample"
             */
            'telegram' => $this->telegram,

            /**
             * The WhatsApp number or link.
             * @var string|null $whatsapp
             * @example "https://wa.me/1234567890"
             */
            'whatsapp' => $this->whatsapp,

            /**
             * The status of the social setting (true for active, false for inactive).
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

            /**
             * The timestamp when the record was last deleted.
             * @var string|null $deleted_at
             * @example "2024-07-19 12:30:00"
             */
            'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null,
        ];
    }
}
