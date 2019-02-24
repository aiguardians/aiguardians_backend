<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Group;
use App\Http\Resources\api\GroupResource;

class GroupController extends Controller
{

    public function index(Request $request, $id) {
        $data['message'] = 'ERROR';
        $data['result'] = new GroupResource(Group::findOrFail($id));
        $data['message'] = 'OK';
        return response()->json($data);
    }

}
