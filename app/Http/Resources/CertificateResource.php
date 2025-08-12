<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CertificateResource extends JsonResource
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
             * The unique identifier for the certificate.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the associated certificate template.
             * @var int $certificate_template_id
             * @example 1
             */
            'certificate_template_id' => $this->certificate_template_id,

            /**
             * The title of the associated certificate template.
             * @var string|null $certificate_template_title
             * @example "Graduation Certificate Template"
             */
            'certificate_template_title' => $this->whenLoaded('certificateTemplate', fn() => $this->certificateTemplate->title),

            /**
             * The ID of the user to whom the certificate is issued.
             * @var int $user_id
             * @example 101
             */
            'user_id' => $this->user_id,

            /**
             * The name of the user to whom the certificate is issued.
             * @var string|null $user_name
             * @example "Student Name"
             */
            'user_name' => $this->whenLoaded('user', fn() => $this->user->name),

            /**
             * The date the certificate was issued.
             * @var string $issue_date
             * @example "2024-06-30"
             */
            'issue_date' => $this->issue_date,

            /**
             * The unique certificate number.
             * @var string|null $certificate_no
             * @example "CERT-2024-001"
             */
            'certificate_no' => $this->certificate_no,

            /**
             * The status of the certificate (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the certificate.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the certificate.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the certificate.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the certificate.
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
