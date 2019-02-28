<?php

namespace App\Http\Resources\api;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'id' => $this->id,
            'course' => new \App\Http\Resources\api\CourseResource($this->course),
            'name' => $this->name,
            'description' => $this->description,
            'deadline' => $this->deadline->format('d.m.Y'),
            'isToday' => $this->deadline->format('d.m.Y')==date('d.m.Y'),
            'remaining_oiginal' => $this->remaining,
            'remaining' => $this->remaining['days']?$this->remaining['days'].' days':($this->remaining['hours']?$this->remaining['hours'].' hours':$this->remaining['minutes'].' minutes'),
            'class' => $this->remaining['days']<1?'t-red':($this->remaining['days']<2?'t-orange':'t-green'),
        ];
    }
}
