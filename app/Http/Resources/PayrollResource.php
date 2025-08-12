<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PayrollResource extends JsonResource
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
             * The unique identifier for the payroll record.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the user (employee) for whom the payroll is generated.
             * @var int $user_id
             * @example 201
             */
            'user_id' => $this->user_id,

            /**
             * The name of the user (employee).
             * @var string|null $user_name
             * @example "Professor Jane"
             */
            'user_name' => $this->whenLoaded('user', fn() => $this->user->name),

            /**
             * The month for which the payroll is generated.
             * @var string $month
             * @example "July"
             */
            'month' => $this->month,

            /**
             * The year for which the payroll is generated.
             * @var string $year
             * @example "2024"
             */
            'year' => $this->year,

            /**
             * The basic salary amount.
             * @var float $basic_salary
             * @example 5000.00
             */
            'basic_salary' => $this->basic_salary,

            /**
             * The total allowance amount.
             * @var float $total_allowance
             * @example 500.00
             */
            'total_allowance' => $this->total_allowance,

            /**
             * The total deduction amount.
             * @var float $total_deduction
             * @example 150.00
             */
            'total_deduction' => $this->total_deduction,

            /**
             * The net salary amount.
             * @var float $net_salary
             * @example 5350.00
             */
            'net_salary' => $this->net_salary,

            /**
             * The payment date of the payroll.
             * @var string|null $payment_date
             * @example "2024-07-31"
             */
            'payment_date' => $this->payment_date,

            /**
             * The status of the payroll (e.g., 0 for unpaid, 1 for paid).
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
