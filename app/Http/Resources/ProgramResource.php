<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ProgramResource extends JsonResource
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
             * The unique identifier for the program.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the program.
             * @var string $title
             * @example "Bachelor of Science in Computer Science"
             */
            'title' => $this->title,

            /**
             * The short code or abbreviation for the program.
             * @var string|null $short_code
             * @example "BSCS"
             */
            'short_code' => $this->short_code,

            /**
             * The description of the program.
             * @var string|null $description
             * @example "A comprehensive program covering fundamental and advanced topics in computer science."
             */
            'description' => $this->description,

            /**
             * The status of the program (true for active, false for inactive).
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
             * The semesters associated with this program.
             * @var \App\Http\Resources\SemesterResource[] $semesters
             */
            'semesters' => SemesterResource::collection($this->whenLoaded('semesters')),

            /**
             * The sections associated with this program.
             * @var \App\Http\Resources\SectionResource[] $sections
             */
            'sections' => SectionResource::collection($this->whenLoaded('sections')),

            /**
             * The subjects associated with this program.
             * @var \App\Http\Resources\SubjectResource[] $subjects
             */
            'subjects' => SubjectResource::collection($this->whenLoaded('subjects')),

            /**
             * The class rooms associated with this program.
             * @var \App\Http\Resources\ClassRoomResource[] $classRooms
             */
            'class_rooms' => ClassRoomResource::collection($this->whenLoaded('classRooms')),

            /**
             * The sessions associated with this program.
             * @var \App\Http\Resources\SessionResource[] $sessions
             */
            'sessions' => SessionResource::collection($this->whenLoaded('sessions')),
        ];
    }
}
