<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ApplicationResource extends JsonResource
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
             * The unique identifier for the application.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The registration number of the application.
             * @var string|null $registration_no
             * @example "APP-2024-001"
             */
            'registration_no' => $this->registration_no,

            /**
             * The ID of the associated batch.
             * @var int|null $batch_id
             * @example 1
             */
            'batch_id' => $this->batch_id,

            /**
             * The title of the associated batch.
             * @var string|null $batch_title
             * @example "Fall 2024"
             */
            'batch_title' => $this->whenLoaded('batch', fn() => $this->batch->title),

            /**
             * The ID of the associated program.
             * @var int|null $program_id
             * @example 1
             */
            'program_id' => $this->program_id,

            /**
             * The title of the associated program.
             * @var string|null $program_title
             * @example "Computer Science"
             */
            'program_title' => $this->whenLoaded('program', fn() => $this->program->title),

            /**
             * The date the application was submitted.
             * @var string|null $apply_date
             * @example "2024-07-15"
             */
            'apply_date' => $this->apply_date,

            /**
             * The first name of the applicant.
             * @var string $first_name
             * @example "John"
             */
            'first_name' => $this->first_name,

            /**
             * The last name of the applicant.
             * @var string $last_name
             * @example "Doe"
             */
            'last_name' => $this->last_name,

            /**
             * The father's name of the applicant.
             * @var string|null $father_name
             * @example "Richard Doe"
             */
            'father_name' => $this->father_name,

            /**
             * The mother's name of the applicant.
             * @var string|null $mother_name
             * @example "Jane Doe"
             */
            'mother_name' => $this->mother_name,

            /**
             * The father's occupation.
             * @var string|null $father_occupation
             * @example "Engineer"
             */
            'father_occupation' => $this->father_occupation,

            /**
             * The mother's occupation.
             * @var string|null $mother_occupation
             * @example "Teacher"
             */
            'mother_occupation' => $this->mother_occupation,

            /**
             * Path to the father's photo.
             * @var string|null $father_photo
             * @example "/uploads/photos/father_john.jpg"
             */
            'father_photo' => $this->father_photo,

            /**
             * Path to the mother's photo.
             * @var string|null $mother_photo
             * @example "/uploads/photos/mother_jane.jpg"
             */
            'mother_photo' => $this->mother_photo,

            /**
             * The country of the applicant.
             * @var string|null $country
             * @example "USA"
             */
            'country' => $this->country,

            /**
             * The ID of the present province.
             * @var int|null $present_province
             * @example 1
             */
            'present_province' => $this->present_province,

            /**
             * The name of the present province.
             * @var string|null $present_province_name
             * @example "California"
             */
            'present_province_name' => $this->whenLoaded('presentProvince', fn() => $this->presentProvince->title),

            /**
             * The ID of the present district.
             * @var int|null $present_district
             * @example 5
             */
            'present_district' => $this->present_district,

            /**
             * The name of the present district.
             * @var string|null $present_district_name
             * @example "Los Angeles"
             */
            'present_district_name' => $this->whenLoaded('presentDistrict', fn() => $this->presentDistrict->title),

            /**
             * The present village/locality.
             * @var string|null $present_village
             * @example "Hollywood"
             */
            'present_village' => $this->present_village,

            /**
             * The present address.
             * @var string|null $present_address
             * @example "123 Main St, Apt 4B"
             */
            'present_address' => $this->present_address,

            /**
             * The ID of the permanent province.
             * @var int|null $permanent_province
             * @example 1
             */
            'permanent_province' => $this->permanent_province,

            /**
             * The name of the permanent province.
             * @var string|null $permanent_province_name
             * @example "California"
             */
            'permanent_province_name' => $this->whenLoaded('permanentProvince', fn() => $this->permanentProvince->title),

            /**
             * The ID of the permanent district.
             * @var int|null $permanent_district
             * @example 5
             */
            'permanent_district' => $this->permanent_district,

            /**
             * The name of the permanent district.
             * @var string|null $permanent_district_name
             * @example "Los Angeles"
             */
            'permanent_district_name' => $this->whenLoaded('permanentDistrict', fn() => $this->permanentDistrict->title),

            /**
             * The permanent village/locality.
             * @var string|null $permanent_village
             * @example "Hollywood"
             */
            'permanent_village' => $this->permanent_village,

            /**
             * The permanent address.
             * @var string|null $permanent_address
             * @example "123 Main St, Apt 4B"
             */
            'permanent_address' => $this->permanent_address,

            /**
             * The gender of the applicant (e.g., 1 for Male, 2 for Female).
             * @var int $gender
             * @example 1
             */
            'gender' => $this->gender,

            /**
             * The date of birth of the applicant.
             * @var string $dob
             * @example "2000-01-01"
             */
            'dob' => $this->dob,

            /**
             * The email address of the applicant.
             * @var string $email
             * @example "john.doe@example.com"
             */
            'email' => $this->email,

            /**
             * The phone number of the applicant.
             * @var string|null $phone
             * @example "+1234567890"
             */
            'phone' => $this->phone,

            /**
             * The emergency phone number.
             * @var string|null $emergency_phone
             * @example "+1987654321"
             */
            'emergency_phone' => $this->emergency_phone,

            /**
             * The religion of the applicant.
             * @var string|null $religion
             * @example "Christianity"
             */
            'religion' => $this->religion,

            /**
             * The caste of the applicant.
             * @var string|null $caste
             * @example "General"
             */
            'caste' => $this->caste,

            /**
             * The mother tongue of the applicant.
             * @var string|null $mother_tongue
             * @example "English"
             */
            'mother_tongue' => $this->mother_tongue,

            /**
             * The marital status of the applicant (e.g., 1 for Single, 2 for Married).
             * @var int|null $marital_status
             * @example 1
             */
            'marital_status' => $this->marital_status,

            /**
             * The blood group of the applicant (e.g., 1 for A+, 2 for B-).
             * @var int|null $blood_group
             * @example 1
             */
            'blood_group' => $this->blood_group,

            /**
             * The nationality of the applicant.
             * @var string|null $nationality
             * @example "American"
             */
            'nationality' => $this->nationality,

            /**
             * The national ID number.
             * @var string|null $national_id
             * @example "123-456-789"
             */
            'national_id' => $this->national_id,

            /**
             * The passport number.
             * @var string|null $passport_no
             * @example "P1234567"
             */
            'passport_no' => $this->passport_no,

            /**
             * The name of the last attended school.
             * @var string|null $school_name
             * @example "Central High School"
             */
            'school_name' => $this->school_name,

            /**
             * The exam ID from school.
             * @var string|null $school_exam_id
             * @example "SCH-EXAM-001"
             */
            'school_exam_id' => $this->school_exam_id,

            /**
             * The graduation field from school.
             * @var string|null $school_graduation_field
             * @example "Science"
             */
            'school_graduation_field' => $this->school_graduation_field,

            /**
             * The graduation year from school.
             * @var string|null $school_graduation_year
             * @example "2018"
             */
            'school_graduation_year' => $this->school_graduation_year,

            /**
             * The graduation point/GPA from school.
             * @var string|null $school_graduation_point
             * @example "4.5"
             */
            'school_graduation_point' => $this->school_graduation_point,

            /**
             * Path to the school transcript.
             * @var string|null $school_transcript
             * @example "/uploads/transcripts/school_john.pdf"
             */
            'school_transcript' => $this->school_transcript,

            /**
             * Path to the school certificate.
             * @var string|null $school_certificate
             * @example "/uploads/certificates/school_john.pdf"
             */
            'school_certificate' => $this->school_certificate,

            /**
             * The name of the last attended college.
             * @var string|null $collage_name
             * @example "City College"
             */
            'collage_name' => $this->collage_name,

            /**
             * The exam ID from college.
             * @var string|null $collage_exam_id
             * @example "COL-EXAM-001"
             */
            'collage_exam_id' => $this->collage_exam_id,

            /**
             * The graduation field from college.
             * @var string|null $collage_graduation_field
             * @example "Computer Science"
             */
            'collage_graduation_field' => $this->collage_graduation_field,

            /**
             * The graduation year from college.
             * @var string|null $collage_graduation_year
             * @example "2022"
             */
            'collage_graduation_year' => $this->collage_graduation_year,

            /**
             * The graduation point/GPA from college.
             * @var string|null $collage_graduation_point
             * @example "3.8"
             */
            'collage_graduation_point' => $this->collage_graduation_point,

            /**
             * Path to the college transcript.
             * @var string|null $collage_transcript
             * @example "/uploads/transcripts/college_john.pdf"
             */
            'collage_transcript' => $this->collage_transcript,

            /**
             * Path to the college certificate.
             * @var string|null $collage_certificate
             * @example "/uploads/certificates/college_john.pdf"
             */
            'collage_certificate' => $this->collage_certificate,

            /**
             * Path to the applicant's photo.
             * @var string|null $photo
             * @example "/uploads/photos/john_doe.jpg"
             */
            'photo' => $this->photo,

            /**
             * Path to the applicant's signature.
             * @var string|null $signature
             * @example "/uploads/signatures/john_doe.png"
             */
            'signature' => $this->signature,

            /**
             * The application fee amount.
             * @var float|null $fee_amount
             * @example 50.00
             */
            'fee_amount' => $this->fee_amount,

            /**
             * The payment status (e.g., 0 for unpaid, 1 for paid).
             * @var int $pay_status
             * @example 1
             */
            'pay_status' => $this->pay_status,

            /**
             * The payment method used (e.g., 1 for online, 2 for cash).
             * @var int|null $payment_method
             * @example 1
             */
            'payment_method' => $this->payment_method,

            /**
             * The overall status of the application (e.g., 0 for pending, 1 for approved).
             * @var int $status
             * @example 1
             */
            'status' => $this->status,

            /**
             * The ID of the user who created the application.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the application.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the application.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the application.
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
