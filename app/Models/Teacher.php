<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Models\Course;
class Teacher extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'teachers';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['user_id', 'appointment', 'regalia', 'first_name', 'last_name', 'tmp_user_id', 'image'];
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
        $course_ids = \App\Models\Course::select('id')->where('lecture_teacher_id', $this->id)
                                                      ->orWhere('lab_teacher_id', $this->id)
                                                      ->orWhere('practice_teacher_id', $this->id)
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

    public function getScheduleByDay($day){
      $course_ids = \App\Models\Course::select('id')->where('lecture_teacher_id', $this->id)
                                                    ->orWhere('lab_teacher_id', $this->id)
                                                    ->orWhere('practice_teacher_id', $this->id)
                                                    ->pluck('id')
                                                    ->toArray();
      $items = Schedule::where('day',$day)->whereIn('course_id', $course_ids)->orderBy('day', 'ASC')->orderBy('start_time', 'ASC')->orderBy('course_id', 'ASC')->get();
      if(count($items) == 0){
        return [
            'status' => 'OK',
            'class' => 'msg msg-left',
            'component' => 'default',
            'content' =>"There is no courses on ".date('l', strtotime("Sunday + {$day} days"))
        ];
      }
      foreach($items as $item) {
          $schedule[$item->day][]=new \App\Http\Resources\api\ScheduleResource($item);
      }
      return [
          'status' => 'OK',
          'component' => 'schedule',
          'content' => $schedule
      ];
    }


    public function getScheduleByDayAndSpecialization($day, $specialization, $group) {
      $group_id = Group::where('name', $specialization.'-'.$group)->firstOrFail()->id;
        if (isset($day)){
          $course_ids = Course::select('id')->where('group_id', $group_id)->pluck('id')->toArray();
          $items = Schedule::where('day', $day)->whereIn('course_id', $course_ids)->orderBy('day', 'ASC')->orderBy('start_time', 'ASC')->orderBy('course_id', 'ASC')->get();
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

    public function getTeacherSchedule($first_name){
      $teacher_id = Teacher::select('id')->where('first_name', $first_name)->firstOrFail()->id;
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function user() {
        return $this->belongsTo('\App\User', 'user_id');
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

    public function getFirstNameAttribute() {
        return isset($this->attributes['first_name'])?$this->attributes['first_name']:$this->user->name;
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
        $destination_path = "teachers";

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
        else
        {
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        }
    }

}
