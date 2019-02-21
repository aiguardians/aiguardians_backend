<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Course extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'courses';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'group_id', 'lecture_teacher_id', 'lab_teacher_id', 'practice_teacher_id', 'tmp_course_id'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function group() {
        return $this->belongsTo('App\Models\Group', 'group_id');
    }

    public function lectureTeacher() {
        return $this->belongsTo('App\Models\Teacher', 'lecture_teacher_id');
    }

    public function labTeacher() {
        return $this->belongsTo('App\Models\Teacher', 'lab_teacher_id');
    }

    public function practiceTeacherId() {
        return $this->belongsTo('App\Models\Teacher', 'practice_teacher_id');
    }

    public function schedule() {
        return $this->hasMany('App\Models\Schedule', 'course_id');
    }

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
