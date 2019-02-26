<?php

namespace App\Http\Resources\api;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'group' => new \App\Http\Resources\api\GroupResource($this->course->group),
            'course' => new \App\Http\Resources\api\CourseResource($this->course),
            'teacher' => new \App\Http\Resources\api\TeacherResource($this->getTeacher()),
            'subject_type' => $this->subject_type,
            'room' => $this->room,
            'day' => $this->day,
            'time' => [
                'start_time' => date('H:i', strtotime($this->start_time)),
                'end_time' => date('H:i', strtotime($this->end_time)),
                'current' => $this->isCurrent(),
                'next' => $this->isNext()
            ],
        ];
    }
}
