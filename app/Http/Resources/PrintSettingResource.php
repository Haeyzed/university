<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PrintSettingResource extends JsonResource
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
             * The unique identifier for the print setting.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the print setting.
             * @var string $title
             * @example "Report Print Settings"
             */
            'title' => $this->title,

            /**
             * The content for the header of the printed document.
             * @var string|null $header
             * @example "University Official Report"
             */
            'header' => $this->header,

            /**
             * The content for the footer of the printed document.
             * @var string|null $footer
             * @example "Confidential"
             */
            'footer' => $this->footer,

            /**
             * The path to the logo image on the printed document.
             * @var string|null $logo
             * @example "/uploads/print_settings/university_logo.png"
             */
            'logo' => $this->logo,

            /**
             * The path to the signature image on the printed document.
             * @var string|null $signature
             * @example "/uploads/print_settings/director_signature.png"
             */
            'signature' => $this->signature,

            /**
             * The status of the print setting (true for active, false for inactive).
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
