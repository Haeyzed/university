<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ApplicationSettingResource extends JsonResource
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
             * The unique identifier for the application setting.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * A unique slug for the setting.
             * @var string $slug
             * @example "admission-form"
             */
            'slug' => $this->slug,

            /**
             * The title of the application setting.
             * @var string|null $title
             * @example "Admission Form Settings"
             */
            'title' => $this->title,

            /**
             * Content for the left side of the header.
             * @var string|null $header_left
             * @example "University Logo"
             */
            'header_left' => $this->header_left,

            /**
             * Content for the center of the header.
             * @var string|null $header_center
             * @example "Application Form"
             */
            'header_center' => $this->header_center,

            /**
             * Content for the right side of the header.
             * @var string|null $header_right
             * @example "Contact Info"
             */
            'header_right' => $this->header_right,

            /**
             * The main body content of the setting.
             * @var string|null $body
             * @example "This section configures the fields and layout of the student application form."
             */
            'body' => $this->body,

            /**
             * Content for the left side of the footer.
             * @var string|null $footer_left
             * @example "Copyright 2024"
             */
            'footer_left' => $this->footer_left,

            /**
             * Content for the center of the footer.
             * @var string|null $footer_center
             * @example "Page 1 of 1"
             */
            'footer_center' => $this->footer_center,

            /**
             * Content for the right side of the footer.
             * @var string|null $footer_right
             * @example "Powered by Vercel"
             */
            'footer_right' => $this->footer_right,

            /**
             * Path to the left logo image.
             * @var string|null $logo_left
             * @example "/uploads/logos/logo_left.png"
             */
            'logo_left' => $this->logo_left,

            /**
             * Path to the right logo image.
             * @var string|null $logo_right
             * @example "/uploads/logos/logo_right.png"
             */
            'logo_right' => $this->logo_right,

            /**
             * Path to the background image.
             * @var string|null $background
             * @example "/uploads/backgrounds/app_bg.jpg"
             */
            'background' => $this->background,

            /**
             * The default fee amount for applications.
             * @var float|null $fee_amount
             * @example 75.00
             */
            'fee_amount' => $this->fee_amount,

            /**
             * Indicates if online payment is enabled.
             * @var bool $pay_online
             * @example true
             */
            'pay_online' => (bool)$this->pay_online,

            /**
             * The status of the application setting (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

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
