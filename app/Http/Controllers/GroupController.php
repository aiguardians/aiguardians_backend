<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Group;
use App\Http\Resources\api\GroupResource;
use App\Http\Resources\api\StudentResource;

class GroupController extends Controller
{

    public function index(Request $request, $id) {
        $data['message'] = 'ERROR';
        $data['result'] = new GroupResource(Group::findOrFail($id));
        $data['message'] = 'OK';
        return response()->json($data);
    }

    public function students(Request $request, $id) {
        $group = Group::findOrFail($id);
        $data['group'] = new GroupResource($group);
        $data['students'] = [];
        foreach($group->students as $student) {
            $data['students'][] = new StudentResource($student);
        }
        return response()->json($data);
    }

}
