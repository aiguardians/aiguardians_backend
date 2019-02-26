<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\CRUD\CrudTrait; // <------------------------------- this one
use Spatie\Permission\Traits\HasRoles;// <---------------------- and this one

class User extends Authenticatable
{
    use CrudTrait; // <----- this
    use HasRoles; // <------ and this
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFirstNameAttribute() {
        return $this->student?$this->student->first_name:$this->teacher->first_name;
    }

    public function getLastNameAttribute() {
        return $this->student?$this->student->last_name:$this->teacher->last_name;
    }



    public function teacher() {
        return $this->hasOne('\App\Models\Teacher', 'user_id');
    }

    public function student() {
        return $this->hasOne('\App\Models\Student', 'user_id');
    }

}
