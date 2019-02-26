<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Models\Group;

class Intent extends Model
{

    public static function proccess($data) {
        $intent = $data->topScoringIntent->intent;
        $entities = $data->entities;
        if (method_exists(get_called_class(), $intent)) {
            return self::$intent();
        }
        return ['status' => 'ERROR'];
    }

    public static function GetSchedule() {
        if (auth()->user()->student && false) {
            return auth()->user()->student->getSchedule();
        }
        else {
            return \App\Models\Teacher::find(20)->getSchedule();
        }
    }

}
