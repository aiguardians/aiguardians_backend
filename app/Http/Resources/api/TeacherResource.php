<?php

namespace App\Http\Resources\api;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'appointment' => $this->appointment,
            'regalia' => $this->regalia,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'user' => new \App\Http\Resources\api\UserResource($this->user),
        ];
    }
}
