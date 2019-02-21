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
        $now = Carbon::now();
        $start = new Carbon($this->start_time);
        $end = new Carbon($this->end_time);
        return ($now->diffInMinutes($start)<=50 && $now->diffInMinutes($end)<=50);
    }

    public function isNext() {
        return false;
        // return (self::getNext($this->course->group_id)->id==$this->id);
    }

    public static function getNext($group_id) {
        // return ;
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
