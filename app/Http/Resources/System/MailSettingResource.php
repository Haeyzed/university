<?php

namespace App\Http\Resources\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class MailSettingResource
 *
 * @property int $id The unique identifier for the mail setting.
 * @property string $driver The mail driver type (e.g., smtp, mailgun).
 * @property string $host The mail server host address.
 * @property string $port The mail server port.
 * @property string $username The username for SMTP authentication.
 * @property string $password Masked mail password (always returned as "********").
 * @property string $encryption The encryption type used (e.g., tls, ssl).
 * @property string $sender_email The "from" email address used in mail sending.
 * @property string $sender_name The "from" name used in mail sending.
 * @property string $reply_email The reply-to email address for mail communications.
 * @property bool $status The active status of the mail configuration (true = active).
 * @property int|null $created_by The ID of the user who created the mail setting.
 * @property string|null $created_by_name The name of the user who created the mail setting.
 * @property int|null $updated_by The ID of the user who last updated the mail setting.
 * @property string|null $updated_by_name The name of the user who last updated the mail setting.
 * @property string $created_at The timestamp when the record was created (Y-m-d H:i:s).
 * @property string $updated_at The timestamp when the record was last updated (Y-m-d H:i:s).
 */
class MailSettingResource extends JsonResource
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
             * The unique identifier for the mail setting.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The mail driver (e.g., "smtp", "mailgun").
             * @var string $driver
             * @example "smtp"
             */
            'driver' => $this->driver,

            /**
             * The mail host.
             * @var string $host
             * @example "smtp.mailtrap.io"
             */
            'host' => $this->host,

            /**
             * The mail port.
             * @var string $port
             * @example "2525"
             */
            'port' => $this->port,

            /**
             * The mail username.
             * @var string $username
             * @example "your_username"
             */
            'username' => $this->username,

            /**
             * The mail password.
             * @var string $password
             * @example "********"
             */
            'password' => $this->password,

            /**
             * The mail encryption type (e.g., "tls", "ssl").
             * @var string $encryption
             * @example "tls"
             */
            'encryption' => $this->encryption,

            /**
             * The mail from address.
             * @var string $sender_email
             * @example "noreply@example.com"
             */
            'sender_email' => $this->sender_email,

            /**
             * The mail from name.
             * @var string $sender_name
             * @example "University Support"
             */
            'sender_name' => $this->sender_name,

            /**
             * The mail reply-to email.
             * @var string $reply_email
             * @example "support@example.com"
             */
            'reply_email' => $this->reply_email,

            /**
             * The status of the mail setting (true for active, false for inactive).
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
