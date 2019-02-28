<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Student extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'students';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['user_id', 'first_name', 'last_name', 'image'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();
        static::deleting(function($obj) {
            \Storage::disk('uploads')->delete(str_replacce('/uploads', '', $obj->image));
        });
    }



    public function getSchedule() {
        $course_ids = Course::select('id')->where('group_id', $this->groups[0]->id)->pluck('id')->toArray();
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


    public function getScheduleByDay($day){
      $course_ids = Course::select('id')->where('group_id', $this->groups[0]->id)->pluck('id')->toArray();
      $items = Schedule::where('day',$day)->whereIn('course_id', $course_ids)->orderBy('day', 'ASC')->orderBy('start_time', 'ASC')->orderBy('course_id', 'ASC')->get();
      if (count($items) == 0){
        return [
            'status' => 'OK',
            'class' => 'msg msg-left',
            'component' => 'default',
            'content' =>"There is no courses on ".date('l', strtotime("Sunday + {$day} days"))
        ];
      }
      }
      foreach($items as $item) {
          $schedule[$item->day][$item->start_time][]=new \App\Http\Resources\api\ScheduleResource($item);
      }
      return [
          'status' => 'OK',
          'component' => 'schedule',
          'content' => $schedule
      ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function groups() {
        return $this->belongsToMany('App\Models\Group', 'student_group_pivot', 'student_id', 'group_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function getGroupAttribute() {
        return $this->groups[0];
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
    public function getImageAttribute()
    {
        if (isset($this->attributes['image'])) {
            return "/uploads/{$this->attributes['image']}";
        }
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "uploads";
        $destination_path = "students";

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value);
            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
            // 3. Save the path to the database
            $this->attributes[$attribute_name] = $destination_path.'/'.$filename;
        }
    }
}
