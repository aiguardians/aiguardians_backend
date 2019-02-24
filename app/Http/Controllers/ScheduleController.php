<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Http\Resources\api\ScheduleResource;
use App\Models\Group;

class ScheduleController extends Controller
{

    public function group(Request $request, $id) {
        $data['message'] = 'ERROR';
        $data['result'] = [[]];
        $group = Group::findOrFail($id);
        foreach($group->courses as $course) {
            foreach($course->schedule as $item) {
                $data['result'][$item->day][] = new ScheduleResource($item);
            }
        }
        $data['currentDay'] = (Schedule::getCurrent($id)?:Schedule::getNext($id))->day;
        $data['message'] = 'OK';
        return response()->json($data);
    }

}
