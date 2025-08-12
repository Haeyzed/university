<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class TopbarSettingResource
 *
 * @property int $id The unique identifier for the topbar setting.
 * @property string|null $address_title The title for the address in the topbar.
 * @property string|null $address The address displayed in the topbar.
 * @property string|null $email The email address displayed in the topbar.
 * @property string|null $phone The phone number displayed in the topbar.
 * @property string|null $working_hour The working hours displayed in the topbar.
 * @property string|null $about_title The title for the 'About' section in the topbar.
 * @property string|null $about_summery A summary for the 'About' section in the topbar.
 * @property string|null $social_title The title for the social media section in the topbar.
 * @property bool $social_status The status of the social media section.
 * @property bool $status The status of the topbar setting.
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 */
class TopbarSettingResource extends JsonResource
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
             * The unique identifier for the topbar setting.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title for the address in the topbar.
             * @var string|null $address_title
             * @example "Our Location"
             */
            'address_title' => $this->address_title,

            /**
             * The address displayed in the topbar.
             * @var string|null $address
             * @example "123 University Ave, City, Country"
             */
            'address' => $this->address,

            /**
             * The email address displayed in the topbar.
             * @var string|null $email
             * @example "info@university.com"
             */
            'email' => $this->email,

            /**
             * The phone number displayed in the topbar.
             * @var string|null $phone
             * @example "+1-800-UNIVERSITY"
             */
            'phone' => $this->phone,

            /**
             * The working hours displayed in the topbar.
             * @var string|null $working_hour
             * @example "Mon-Fri: 9 AM - 5 PM"
             */
            'working_hour' => $this->working_hour,

            /**
             * The title for the 'About' section in the topbar.
             * @var string|null $about_title
             * @example "About Us"
             */
            'about_title' => $this->about_title,

            /**
             * A summary for the 'About' section in the topbar.
             * @var string|null $about_summery
             * @example "Leading education since 1990."
             */
            'about_summery' => $this->about_summery,

            /**
             * The title for the social media section in the topbar.
             * @var string|null $social_title
             * @example "Follow Us"
             */
            'social_title' => $this->social_title,

            /**
             * The status of the social media section (true for active, false for inactive).
             * @var bool $social_status
             * @example true
             */
            'social_status' => (bool)$this->social_status,

            /**
             * The status of the topbar setting (true for active, false for inactive).
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
