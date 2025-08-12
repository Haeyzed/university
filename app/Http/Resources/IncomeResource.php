<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class IncomeResource extends JsonResource
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
             * The unique identifier for the income.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the income category.
             * @var int $income_category_id
             * @example 1
             */
            'income_category_id' => $this->income_category_id,

            /**
             * The title of the income category.
             * @var string|null $income_category_title
             * @example "Donations"
             */
            'income_category_title' => $this->whenLoaded('incomeCategory', fn() => $this->incomeCategory->title),

            /**
             * The title or description of the income.
             * @var string $title
             * @example "Grant from Research Foundation"
             */
            'title' => $this->title,

            /**
             * The amount of the income.
             * @var float $amount
             * @example 10000.00
             */
            'amount' => $this->amount,

            /**
             * The date of the income.
             * @var string $date
             * @example "2024-07-15"
             */
            'date' => $this->date,

            /**
             * The status of the income (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the income.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the income.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the income.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the income.
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
