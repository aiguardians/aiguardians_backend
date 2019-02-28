<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use \Carbon\Carbon;

class Schedule extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'schedule';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['course_id', 'subject_type', 'room', 'day', 'start_time', 'end_time'];
    // protected $hidden = [];
    // protected $dates = [];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function course() {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    public static function getScheduleByDayAndSpecialization($day, $specialization, $group) {
      $group_id = Group::where('name', $specialization.'-'.$group)->firstOrFail()->id;
      if(!isset($group_id)){
        return [
            'status' => 'OK',
            'class' => 'msg msg-left',
            'component' => 'default',
            'content' =>"There is no such group ".$specialization.'-'.$group
        ];
      }
        if (isset($day)){
          $course_ids = Course::select('id')->where('group_id', $group_id)->pluck('id')->toArray();
          $items = Schedule::where('day', $day)->whereIn('course_id', $course_ids)->orderBy('day', 'ASC')->orderBy('start_time', 'ASC')->orderBy('course_id', 'ASC')->get();
          if(count($items) == 0){
            return [
                'status' => 'OK',
                'class' => 'msg msg-left',
                'component' => 'default',
                'content' =>"No classes on ".$day
            ];
          }
          foreach($items as $item) {
              $schedule[$item->day][$item->start_time][]=new \App\Http\Resources\api\ScheduleResource($item);
          }
          return [
              'status' => 'OK',
              'classes' => 'specializationandgroup',
              'component' => 'schedule',
              'content' => $schedule
          ];
      }
      else{
        $course_ids = Course::select('id')->where('group_id', $group_id)->pluck('id')->toArray();
        $items = Schedule::whereIn('course_id', $course_ids)->orderBy('day', 'ASC')->orderBy('start_time', 'ASC')->orderBy('course_id', 'ASC')->get();

        foreach($items as $item) {
            $schedule[$item->day][$item->start_time][]=new \App\Http\Resources\api\ScheduleResource($item);
        }
        return [
            'status' => 'OK',
            'classes' => 'specializationandgroup',
            'component' => 'schedule',
            'content' => $schedule
        ];
      }
    }

    public static function getScheduleOfTeacherAtParticularDay($first_name, $day){
      $teacher_id = Teacher::select('id')->where('first_name', $first_name)->firstOrFail()->id;
      if(!isset($teacher_id)){
        return [
          'status' => 'OK',
          'class' => 'msg msg-left',
          'component' => 'default',
          'content' =>"There is no teacher ".$first_name
        ];
      }
      $course_ids = \App\Models\Course::select('id')->where('lab_teacher_id', $teacher_id)
                                                    ->orWhere('practice_teacher_id', $teacher_id)
                                                    ->pluck('id')
                                                    ->toArray();
      $items = Schedule::where('day', $day)->whereIn('course_id', $course_ids)->orderBy('day', 'ASC')->orderBy('start_time', 'ASC')->orderBy('course_id', 'ASC')->get();
      foreach($items as $item) {
        $schedule[$item->day][$item->start_time][]=new \App\Http\Resources\api\ScheduleResource($item);
      }
      return [
        'status' => 'OK',
        'component' => 'schedule',
        'content' => $schedule
      ];
    }

    public static function getTeacherSchedule($first_name){
      $teacher_id = Teacher::select('id')->where('first_name', $first_name)->firstOrFail()->id;
      if(!isset($teacher_id)){
        return [
          'status' => 'OK',
          'class' => 'msg msg-left',
          'component' => 'default',
          'content' =>"There is no teacher ".$first_name
        ];
      }
      $course_ids = \App\Models\Course::select('id')->where('lab_teacher_id', $teacher_id)
                                                    ->orWhere('practice_teacher_id', $teacher_id)
                                                    ->pluck('id')
                                                    ->toArray();
      $items = Schedule::whereIn('course_id', $course_ids)->orderBy('day', 'ASC')->orderBy('start_time', 'ASC')->orderBy('course_id', 'ASC')->get();
      foreach($items as $item) {
          $schedule[$item->day][$item->start_time][]=new \App\Http\Resources\api\ScheduleResource($item);
      }
      return [
          'status' => 'OK',
          'component' => 'schedule',
          'content' => $schedule
      ];
    }


    public function getTeacher() {
        $course = $this->course;
        if ($course->lecture_teacher_id && ($this->subject_type=='lecture' || (is_null($course->lab_teacher_id) && is_null($course->practice_teacher_id)))) {
            // return $course->lecture_teacher_id;
            $teacher = \App\Models\Teacher::findOrFail($course->lecture_teacher_id);
        }
        else if ($course->lab_teacher_id && ($this->subject_type=='lab' || $this->subject_type=='lecture' || is_null($course->practice_teacher_id))) {
            // return $course->lab_teacher_id;
            $teacher = \App\Models\Teacher::findOrFail($course->lab_teacher_id);
        }
        else {
            // return $course->practice_teacher_id;
            $teacher = \App\Models\Teacher::findOrFail($course->practice_teacher_id);
        }
        return $teacher;
    }

    public function isCurrent() {
        $current = self::getCurrent($this->course->group_id);
        if ($current) {
            return ($current->id==$this->id);
        }
        return false;
    }

    public function isNext() {
        return (self::getNext($this->course->group_id)->id==$this->id);
    }

    public static function getNext($group_id) {
        $courses = \App\Models\Course::select('id')->where('group_id', $group_id)->pluck('id')->toArray();
        $res = self::whereIn('course_id', $courses)
                       ->orderByRaw("IF(day<DAYOFWEEK(CURDATE())-1 OR (day=DAYOFWEEK(CURDATE())-1 AND CURTIME()>=start_time), CONCAT(DATE_ADD(CURDATE(), INTERVAL (8 + day - IF(DAYOFWEEK(CURDATE())=1, 8, DAYOFWEEK(CURDATE()))) DAY), ' ', start_time), CONCAT(DATE_ADD(CURDATE(), INTERVAL (day+1 - DAYOFWEEK(CURDATE())) DAY), ' ', start_time)) ASC")
                       ->orderBy('course_id', 'ASC')
                       ->first();
        return $res;
    }

    public static function getCurrent($group_id) {
        // $day = date('w');
        // $time = date('H:i:s');
        $res = self::where('day', '=', 'DAY(NOW())')
                     ->where('start_time', '<=', 'CURRENT_TIME()')
                     ->where('end_time', '>=', 'CURRENT_TIME()')
                     ->first();
        return $res;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getStartTimeAttribute() {
        return date('H:i', strtotime($this->attributes['start_time']));
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
