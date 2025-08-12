<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class StudentResource extends JsonResource
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
             * The unique identifier for the student.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the associated user.
             * @var int $user_id
             * @example 101
             */
            'user_id' => $this->user_id,

            /**
             * The name of the associated user.
             * @var string|null $user_name
             * @example "Student Name"
             */
            'user_name' => $this->whenLoaded('user', fn() => $this->user->name),

            /**
             * The ID of the associated student enrollment.
             * @var int $student_enroll_id
             * @example 1
             */
            'student_enroll_id' => $this->student_enroll_id,

            /**
             * The registration number of the student.
             * @var string|null $registration_no
             * @example "STU-2024-001"
             */
            'registration_no' => $this->registration_no,

            /**
             * The first name of the student.
             * @var string $first_name
             * @example "Alice"
             */
            'first_name' => $this->first_name,

            /**
             * The last name of the student.
             * @var string $last_name
             * @example "Smith"
             */
            'last_name' => $this->last_name,

            /**
             * The father's name of the student.
             * @var string|null $father_name
             * @example "Robert Smith"
             */
            'father_name' => $this->father_name,

            /**
             * The mother's name of the student.
             * @var string|null $mother_name
             * @example "Maria Smith"
             */
            'mother_name' => $this->mother_name,

            /**
             * The father's occupation.
             * @var string|null $father_occupation
             * @example "Doctor"
             */
            'father_occupation' => $this->father_occupation,

            /**
             * The mother's occupation.
             * @var string|null $mother_occupation
             * @example "Nurse"
             */
            'mother_occupation' => $this->mother_occupation,

            /**
             * Path to the father's photo.
             * @var string|null $father_photo
             * @example "/uploads/photos/father_robert.jpg"
             */
            'father_photo' => $this->father_photo,

            /**
             * Path to the mother's photo.
             * @var string|null $mother_photo
             * @example "/uploads/photos/mother_maria.jpg"
             */
            'mother_photo' => $this->mother_photo,

            /**
             * The country of the student.
             * @var string|null $country
             * @example "Canada"
             */
            'country' => $this->country,

            /**
             * The ID of the present province.
             * @var int|null $present_province
             * @example 2
             */
            'present_province' => $this->present_province,

            /**
             * The name of the present province.
             * @var string|null $present_province_name
             * @example "Ontario"
             */
            'present_province_name' => $this->whenLoaded('presentProvince', fn() => $this->presentProvince->title),

            /**
             * The ID of the present district.
             * @var int|null $present_district
             * @example 10
             */
            'present_district' => $this->present_district,

            /**
             * The name of the present district.
             * @var string|null $present_district_name
             * @example "Toronto"
             */
            'present_district_name' => $this->whenLoaded('presentDistrict', fn() => $this->presentDistrict->title),

            /**
             * The present village/locality.
             * @var string|null $present_village
             * @example "North York"
             */
            'present_village' => $this->present_village,

            /**
             * The present address.
             * @var string|null $present_address
             * @example "456 Elm St, Unit 10"
             */
            'present_address' => $this->present_address,

            /**
             * The ID of the permanent province.
             * @var int|null $permanent_province
             * @example 2
             */
            'permanent_province' => $this->permanent_province,

            /**
             * The name of the permanent province.
             * @var string|null $permanent_province_name
             * @example "Ontario"
             */
            'permanent_province_name' => $this->whenLoaded('permanentProvince', fn() => $this->permanentProvince->title),

            /**
             * The ID of the permanent district.
             * @var int|null $permanent_district
             * @example 10
             */
            'permanent_district' => $this->permanent_district,

            /**
             * The name of the permanent district.
             * @var string|null $permanent_district_name
             * @example "Toronto"
             */
            'permanent_district_name' => $this->whenLoaded('permanentDistrict', fn() => $this->permanentDistrict->title),

            /**
             * The permanent village/locality.
             * @var string|null $permanent_village
             * @example "North York"
             */
            'permanent_village' => $this->permanent_village,

            /**
             * The permanent address.
             * @var string|null $permanent_address
             * @example "456 Elm St, Unit 10"
             */
            'permanent_address' => $this->permanent_address,

            /**
             * The gender of the student (e.g., 1 for Male, 2 for Female).
             * @var int $gender
             * @example 2
             */
            'gender' => $this->gender,

            /**
             * The date of birth of the student.
             * @var string $dob
             * @example "2002-05-10"
             */
            'dob' => $this->dob,

            /**
             * The email address of the student.
             * @var string $email
             * @example "alice.smith@example.com"
             */
            'email' => $this->email,

            /**
             * The phone number of the student.
             * @var string|null $phone
             * @example "+14165551234"
             */
            'phone' => $this->phone,

            /**
             * The emergency phone number.
             * @var string|null $emergency_phone
             * @example "+14165555678"
             */
            'emergency_phone' => $this->emergency_phone,

            /**
             * The religion of the student.
             * @var string|null $religion
             * @example "Islam"
             */
            'religion' => $this->religion,

            /**
             * The caste of the student.
             * @var string|null $caste
             * @example "OBC"
             */
            'caste' => $this->caste,

            /**
             * The mother tongue of the student.
             * @var string|null $mother_tongue
             * @example "French"
             */
            'mother_tongue' => $this->mother_tongue,

            /**
             * The marital status of the student (e.g., 1 for Single, 2 for Married).
             * @var int|null $marital_status
             * @example 1
             */
            'marital_status' => $this->marital_status,

            /**
             * The blood group of the student (e.g., 1 for A+, 2 for B-).
             * @var int|null $blood_group
             * @example 2
             */
            'blood_group' => $this->blood_group,

            /**
             * The nationality of the student.
             * @var string|null $nationality
             * @example "Canadian"
             */
            'nationality' => $this->nationality,

            /**
             * The national ID number.
             * @var string|null $national_id
             * @example "987-654-321"
             */
            'national_id' => $this->national_id,

            /**
             * The passport number.
             * @var string|null $passport_no
             * @example "P7654321"
             */
            'passport_no' => $this->passport_no,

            /**
             * The name of the last attended school.
             * @var string|null $school_name
             * @example "Maple Leaf High School"
             */
            'school_name' => $this->school_name,

            /**
             * The exam ID from school.
             * @var string|null $school_exam_id
             * @example "SCH-EXAM-002"
             */
            'school_exam_id' => $this->school_exam_id,

            /**
             * The graduation field from school.
             * @var string|null $school_graduation_field
             * @example "Arts"
             */
            'school_graduation_field' => $this->school_graduation_field,

            /**
             * The graduation year from school.
             * @var string|null $school_graduation_year
             * @example "2020"
             */
            'school_graduation_year' => $this->school_graduation_year,

            /**
             * The graduation point/GPA from school.
             * @var string|null $school_graduation_point
             * @example "4.2"
             */
            'school_graduation_point' => $this->school_graduation_point,

            /**
             * Path to the school transcript.
             * @var string|null $school_transcript
             * @example "/uploads/transcripts/school_alice.pdf"
             */
            'school_transcript' => $this->school_transcript,

            /**
             * Path to the school certificate.
             * @var string|null $school_certificate
             * @example "/uploads/certificates/school_alice.pdf"
             */
            'school_certificate' => $this->school_certificate,

            /**
             * The name of the last attended college.
             * @var string|null $collage_name
             * @example "Toronto College"
             */
            'collage_name' => $this->collage_name,

            /**
             * The exam ID from college.
             * @var string|null $collage_exam_id
             * @example "COL-EXAM-002"
             */
            'collage_exam_id' => $this->collage_exam_id,

            /**
             * The graduation field from college.
             * @var string|null $collage_graduation_field
             * @example "Liberal Arts"
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
             * @example "3.5"
             */
            'collage_graduation_point' => $this->collage_graduation_point,

            /**
             * Path to the college transcript.
             * @var string|null $collage_transcript
             * @example "/uploads/transcripts/college_alice.pdf"
             */
            'collage_transcript' => $this->collage_transcript,

            /**
             * Path to the college certificate.
             * @var string|null $collage_certificate
             * @example "/uploads/certificates/college_alice.pdf"
             */
            'collage_certificate' => $this->collage_certificate,

            /**
             * Path to the student's photo.
             * @var string|null $photo
             * @example "/uploads/photos/alice_smith.jpg"
             */
            'photo' => $this->photo,

            /**
             * Path to the student's signature.
             * @var string|null $signature
             * @example "/uploads/signatures/alice_smith.png"
             */
            'signature' => $this->signature,

            /**
             * The status of the student (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the student record.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the student record.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the student record.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the student record.
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
