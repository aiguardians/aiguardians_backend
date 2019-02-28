<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Task extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'tasks';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['course_id', 'name', 'description', 'deadline'];
    // protected $hidden = [];
    protected $dates = ['deadline'];

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
    public function course() {
        return $this->belongsTo('\App\Models\Course', 'course_id');
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
    public function getRemainingAttribute() {
        $now = new \DateTime();
        $future_date = $this->deadline;
        $interval = $future_date->diff($now);
        return [
            'days' => $interval->format('%a'),
            'hours' => $interval->format('%h'),
            'minutes' => $interval->format('%i'),
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setDeadlineAttribute($value) {
        $this->attributes['deadline'] = \Date::parse($value);
    }
}
