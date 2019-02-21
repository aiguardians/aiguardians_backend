<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Models\Group;

class Intent extends Model
{

    public static function proccess($data) {
        $intent = $data->topScoringIntent->intent;
        if (method_exists(get_called_class(), $intent)) {
            return self::$intent();
        }
        return 'false';
    }

    public static function GetSchedule() {
        $group = Group::where('id', 1)->with(['courses' => function($query) {
            $query->with(['schedule' => function($query) {
                $query->orderBy('day', 'ASC')->orderBy('start_time', 'ASC');
            }]);
        }])->firstOrFail();
        $schedule['group'] = new \App\Http\Resources\api\GroupResource($group);
        foreach($group->courses as $course) {
            foreach($course->schedule as $item) {
                $schedule['schedule'][$item->day][]=new \App\Http\Resources\api\ScheduleResource($item);
            }
        }
        return [
            'component' => 'schedule',
            'content' => $schedule
        ];
    }

}
