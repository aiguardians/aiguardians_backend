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
    return \App\Models\Student::find(1)->getScheduleByDayAndSpecialization(null,'SIS','1811');
    // return date('w',strtotime('monday'));
})->name('test');

Route::post('/test/video', function() {
    return dd(request()->file->store('videos', 'uploads'));
});




Route::middleware(['auth'])->group(function() {
    Route::post('/attendance', function() {
        return dd(request()->date);
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
            foreach($courses as $course) {
                $data[]= new \App\Http\Resources\CourseResource($course);
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
