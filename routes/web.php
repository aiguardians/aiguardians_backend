<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test', function() {

})->name('test');

Route::post('/test/video', function() {
    $schedule_id = 120;
    $attendance = json_decode(request()->attendance);
    $data = json_decode(request()->result);
    foreach($attendance as $item) {
        if ($item->cnt==0) {
            continue;
        }
        $t = new \App\Models\Attendance;
        $t->student_id = $item->id;
        $t->schedule_id = $schedule_id;
        $t->created_at = "2019-03-05 21:29:55";
        $t->save();
    }
    $e = new \App\Models\Emotion;

    $e->video = request()->file;
    $e->data = $data;
    $e->schedule_id = $schedule_id;
    $e->save();
    return "OK";
});




Route::middleware(['auth'])->group(function() {
    Route::post('/attendance', function() {
        $schedules = \App\Models\Course::findOrFail(request()->course)->schedule()->where('day', date('w', strtotime(request()->date)))->get();
        $res = [];
        foreach($schedules as $s) {
            $data = \App\Models\Attendance::where('schedule_id', $s->id)->get();//->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d')=".date('Y-m-d', strtotime(request()->date)))
            foreach($data as $item) {
                $res[$s->course->group->name][] = new \App\Http\Resources\api\AttendanceResource($item);
            }
        }
        return response()->json($res);
    });
    Route::get('/video', function() {
        return \App\Models\Emotion::first()->video;
    });
    Route::get('/emotions', function() {
        return \App\Models\Emotion::first()->data;
    });

    Route::post('/set/image', function() {
        if (auth()->user()->student) {
            auth()->user()->student->image = request()->image;
            auth()->user()->student->save();
        }
        else {
            auth()->user()->teacher->image = request()->image;
            auth()->user()->teacher->save();
        }
    });
    Route::get('/courses', function() {
        if (auth()->user()->student) {
            $courses = auth()->user()->student->groups[0]->courses;
            $data = [];
            foreach($courses as $course) {
                $data[]=new \App\Http\Resources\api\CourseResource($course);
            }
        }
        else {
            $courses = \App\Models\Course::where('lecture_teacher_id', auth()->user()->teacher->id)
                                          ->orWhere('lab_teacher_id', auth()->user()->teacher->id)
                                          ->orWhere('practice_teacher_id', auth()->user()->teacher->id)
                                          ->get();
            $data = [];
            foreach($courses as $course) {
                $data[]= new App\Http\Resources\api\CourseResource($course);
            }
        }
        return response()->json($data);
    });

    Route::get('/deadlines', function() {
        $data = [];
        if (auth()->user()->student) {
            $courses = auth()->user()->student->groups[0]->courses;
            foreach($courses as $course) {
                $tasks = $course->tasks()->where('deadline', '>', date('Y-m-d H:i:s'))->orderBy('deadline', 'ASC')->get();
                foreach($tasks as $task) {
                    $data[$task->deadline->format('Y.m.d')][]=new \App\Http\Resources\api\TaskResource($task);
                }
            }
        }
        else {
            $course_ids = \App\Models\Course::select('id')->where('lecture_teacher_id', auth()->user()->teacher->id)
                                                          ->orWhere('lab_teacher_id', auth()->user()->teacher->id)
                                                          ->orWhere('practice_teacher_id', auth()->user()->teacher->id)
                                                          ->pluck('id')
                                                          ->toArray();
            $courses = \App\Models\Task::whereIn('course_id', $course_ids)->where('deadline', '>', date('Y-m-d H:i:s'))->orderBy('deadline', 'ASC')->get();
            foreach($courses as $task) {
                $data[$task->deadline->format('Y.m.d')][]= new \App\Http\Resources\api\TaskResource($task);
            }
        }
        return response()->json($data);
    });


    Route::view('/test/video', 'pages.video')->name('test');
    Route::get('/home/{page?}', 'PageController@home')->name('home');
    Route::get('/chat', 'ChatController@index')->name('chat');
    Route::get('/query', 'ChatController@query')->name('chat-query');
});

Route::view('/welcome', 'pages.welcome')->name('welcome');
Route::view('/', 'pages.welcome')->name('welcome');


Auth::routes();
