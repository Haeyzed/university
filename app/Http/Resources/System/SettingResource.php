<?php

namespace App\Http\Resources\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class SettingResource
 *
 * @property int $id The unique identifier for the setting.
 * @property string $title The main title of the website or academy.
 * @property string|null $academy_code The unique code for the academy.
 * @property string|null $meta_title The SEO meta title for the website.
 * @property string|null $meta_description The SEO meta description for the website.
 * @property string|null $meta_keywords The SEO meta keywords for the website.
 * @property string|null $logo_path The filename of the logo image.
 * @property string|null $logo_url The full URL to the logo image.
 * @property string|null $favicon_path The filename of the favicon image.
 * @property string|null $favicon_url The full URL to the favicon image.
 * @property string|null $phone The contact phone number.
 * @property string|null $email The contact email address.
 * @property string|null $fax The contact fax number.
 * @property string|null $address The physical address of the institution.
 * @property string|null $language The default language code.
 * @property string|null $date_format The default date format (e.g., Y-m-d).
 * @property string|null $time_format The default time format (e.g., H:i:s).
 * @property string|null $week_start The start day of the week (e.g., 0 = Sunday).
 * @property string|null $time_zone The default timezone (e.g., Asia/Kathmandu).
 * @property string|null $currency The default currency code (e.g., USD).
 * @property string|null $currency_symbol The symbol for the default currency (e.g., $).
 * @property int $decimal_place The number of decimal places for currency formatting.
 * @property string|null $copyright_text The copyright text shown in the footer.
 * @property bool $status The publication status of the setting (true for active).
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string|null $deleted_at The timestamp when the record was deleted, if applicable.
 */
class SettingResource extends JsonResource
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
             * The unique identifier for the setting.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The main title of the website/academy.
             * @var string $title
             * @example "My University"
             */
            'title' => $this->title,

            /**
             * The unique code for the academy.
             * @var string|null $academy_code
             * @example "UNI-001"
             */
            'academy_code' => $this->academy_code,

            /**
             * The SEO meta title for the website.
             * @var string|null $meta_title
             * @example "My University - Education Excellence"
             */
            'meta_title' => $this->meta_title,

            /**
             * The SEO meta description for the website.
             * @var string|null $meta_description
             * @example "Leading university offering diverse academic programs."
             */
            'meta_description' => $this->meta_description,

            /**
             * The SEO meta keywords for the website.
             * @var string|null $meta_keywords
             * @example "university, education, courses, degrees"
             */
            'meta_keywords' => $this->meta_keywords,

            /**
             * The filename of the logo image.
             * @var string|null $logo_path
             * @example "logo.png"
             */
            'logo_path' => $this->logo_path,

            /**
             * The full URL to the logo image.
             * @var string|null $logo_url
             * @example "https://your-bucket.s3.amazonaws.com/settings/logo.png"
             */
            'logo_url' => $this->logo_url,

            /**
             * The filename of the favicon image.
             * @var string|null $favicon_path
             * @example "favicon.ico"
             */
            'favicon_path' => $this->favicon_path,

            /**
             * The full URL to the favicon image.
             * @var string|null $favicon_url
             * @example "https://your-bucket.s3.amazonaws.com/settings/favicon.ico"
             */
            'favicon_url' => $this->favicon_url,

            /**
             * The contact phone number.
             * @var string|null $phone
             * @example "+1234567890"
             */
            'phone' => $this->phone,

            /**
             * The contact email address.
             * @var string|null $email
             * @example "info@example.com"
             */
            'email' => $this->email,

            /**
             * The contact fax number.
             * @var string|null $fax
             * @example "+1234567891"
             */
            'fax' => $this->fax,

            /**
             * The physical address.
             * @var string|null $address
             * @example "123 University Ave, City, Country"
             */
            'address' => $this->address,

            /**
             * The default language code (e.g., "en").
             * @var string|null $language
             * @example "en"
             */
            'language' => $this->language,

            /**
             * The default date format (e.g., "Y-m-d").
             * @var string|null $date_format
             * @example "Y-m-d"
             */
            'date_format' => $this->date_format,

            /**
             * The default time format (e.g., "H:i:s").
             * @var string|null $time_format
             * @example "H:i:s"
             */
            'time_format' => $this->time_format,

            /**
             * The start day of the week (0 for Sunday, 1 for Monday).
             * @var string|null $week_start
             * @example "1"
             */
            'week_start' => $this->week_start,

            /**
             * The default timezone (e.g., "Asia/Kathmandu").
             * @var string|null $time_zone
             * @example "Asia/Kathmandu"
             */
            'time_zone' => $this->time_zone,

            /**
             * The default currency code (e.g., "USD").
             * @var string|null $currency
             * @example "USD"
             */
            'currency' => $this->currency,

            /**
             * The symbol for the default currency (e.g., "$").
             * @var string|null $currency_symbol
             * @example "$"
             */
            'currency_symbol' => $this->currency_symbol,

            /**
             * The number of decimal places for currency.
             * @var int $decimal_place
             * @example 2
             */
            'decimal_place' => $this->decimal_place,

            /**
             * The copyright text for the footer.
             * @var string|null $copyright_text
             * @example "© 2025 My University. All rights reserved."
             */
            'copyright_text' => $this->copyright_text,

            /**
             * The status of the setting (true for active, false for inactive).
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
