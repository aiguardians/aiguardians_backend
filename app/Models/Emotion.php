<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Emotion extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'emotions';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['schedule_id', 'data', 'video'];
    protected $casts = [
        'data' => 'array',
    ];
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
    public function schedule() {
        return $this->belongsTo('\App\Models\Schedule', 'schedule_id');
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
    public function getVideoAttribute() {
        if (isset($this->attributes['video'])) {
            return "/uploads/{$this->attributes['video']}";
        }
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setVideoAttribute($value) {
        $attribute_name = "video";
        $disk = "uploads";
        $destination_path = "videos";
        $filename = md5($value.time()).".webm";

        $value->move(public_path()."/{$disk}/{$destination_path}", $filename);
        $this->attributes['video'] = "{$destination_path}/".$filename;
    }

}
