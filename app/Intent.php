<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Models\Group;

class Intent extends Model
{

    public static function proccess($data) {
        if ($data->topScoringIntent->score<0.7) {
          return self::None();
        }
        $intent = $data->topScoringIntent->intent;
        $entities_raw = $data->entities;
        $entities = [];
        foreach($entities_raw as $entity) {
          $entities[$entity->type]=$entity;
        }
        // return dd($entities);
        if (method_exists(get_called_class(), $intent)) {
            return self::$intent($entities);
        }
        return ['status' => 'ERROR'];
    }

    public static function None(){
      return [
          'status' => 'OK',
          'class' => 'msg msg-left',
          'component' => 'default',
          'content' =>"I don't understand you."
      ];
    }

    public static function GetAttendance($entities){
        $message = 'Attendance is already checked. Please, visit Profile to see the analysis of emotion.';
        return [
            'status' => 'OK',
            'class' => 'msg msg-left',
            'component' => 'default',
            'content' =>$message
        ];
    }

    public static function GetDeadlinesForDay($day){
      $courses = auth()->user()->student->groups[0]->courses;
      $data = [];
      foreach($courses as $course) {
          $tasks = $course->tasks()->whereRaw("DAY(deadline)={$day} and deadline>=NOW()")->orderBy('deadline', 'ASC')->get();
          foreach($tasks as $task) {
              $data[$task->deadline->format('Y.m.d')][]=new \App\Http\Resources\api\TaskResource($task);
          }
      }
      if(count($data)==0){
        return [
            'status' => 'OK',
            'class' => 'msg msg-left',
            'component' => 'default',
            'content' =>"You are lucky! There is no any deadlines!!!"
        ];
      }
      return [
          'status' => 'OK',
          'class' => 'deadline',
          'component' => 'deadline',
          'content' => $data
      ];
    }

    public static function GetDeadlines($entities){
      if(auth()->user()->student){
        if(isset($entities['Day'])){
          $day = date('w',strtotime($entities['Day']->entity));
          $next = date('d', strtotime('next '.date('l', strtotime("Sunday + {$day} days"))));
          return self::GetDeadlinesForDay($next);
        }
        else{
          $courses = auth()->user()->student->groups[0]->courses;
          $data = [];
          foreach($courses as $course) {
              $tasks = $course->tasks()->where('deadline', '>', date('Y-m-d H:i:s'))->orderBy('deadline', 'ASC')->get();
              foreach($tasks as $task) {
                  $data[$task->deadline->format('Y.m.d')][]=new \App\Http\Resources\api\TaskResource($task);
              }
          }
          if(count($data) == 0){
            return [
              'status' => 'OK',
              'class' => 'msg msg-left',
              'component' => 'default',
                'content' => 'You are lucky! There is no any deadlines!!!'
            ];
          }
          return [
              'status' => 'OK',
              // 'class' => 'deadline'
              'component' => 'deadline',
              'content' => $data
          ];
     }
     }
     else{
       if(isset($entities['Day'])){
         $day = date('w',strtotime($entities['Day']->entity));
         $next = date('d', strtotime('next '.date('l', strtotime("Sunday + {$day} days"))));
         return self::GetDeadlinesForDay($next);
       }
       else{
         $courses = \App\Models\Course::select('id')->where('lecture_teacher_id', auth()->user()->teacher->id)
                                                     ->orWhere('lab_teacher_id', auth()->user()->teacher->id)
                                                     ->orWhere('practice_teacher_id', auth()->user()->teacher->id)
                                                     ->get();
         $data = [];
         foreach($courses as $course) {
             $tasks = $course->tasks()->where('deadline', '>', date('Y-m-d H:i:s'))->orderBy('deadline', 'ASC')->get();
             foreach($tasks as $task) {
                 $data[$task->deadline->format('Y.m.d')][]=new \App\Http\Resources\api\TaskResource($task);
             }
         }
         if(count($data) == 0){
           return [
             'status' => 'OK',
             'class' => 'msg msg-left',
             'component' => 'default',
               'content' => 'There is no any deadlines'
           ];
         }
         return [
             'status' => 'OK',
             // 'class' => 'deadline'
             'component' => 'deadline',
             'content' => $data
         ];
       }
     }
    }


    public static function GetEmotions($entities){
        if(auth()->user()->teacher){
          return 'Need date and course name';
        }
        else{
          return 'You do not have enough permission to see emotions state of students';
        }
    }


    // public static function GetInformation($entities){
    //   $arr = [];
    //   return 'need deadlines';
    // }
    //
    // public static function GetRecommendations($entities){
    //   $arr = [];
    //   return 'need recommendations';
    // }

    public static function GetSchedule($entities) {
        if (auth()->user()->student) {
          if(isset($entities['Day'])){
            if(isset($entities['Group']) && isset($entities['Specialization']))
              return \App\Models\Schedule::getScheduleByDayAndSpecialization(date('w',strtotime($entities['Day']->entity)),$entities['Specialization']->entity,$entities['Group']->entity);
            if(isset($entities['person'])){
                return \App\Models\Schedule::getScheduleOfTeacherAtParticularDay($entities['person']->entity,date('w',strtotime($entities['Day']->entity)));
              }
            else
              return auth()->user()->student->getScheduleByDay(date('w',strtotime($entities['Day']->entity)));
          }
          else {
            if(isset($entities['Group']) && isset($entities['Specialization'])){
              return \App\Models\Schedule::getScheduleByDayAndSpecialization(null,$entities['Specialization']->entity,$entities['Group']->entity);
            }
            if(isset($entities['person'])){
                return \App\Models\Schedule::getTeacherSchedule($entities['person']->entity);
            }
            else
              return auth()->user()->student->getSchedule();
          }
        }
        else {
          if(auth()->user()->teacher){
            if(isset($entities['Day'])){
              if(isset($entities['Group']) && isset($entities['Specialization']))
                return \App\Models\Schedule::getScheduleByDayAndSpecialization(date('w',strtotime($entities['Day']->entity)),$entities['Specialization']->entity,$entities['Group']->entity);
              else if(isset($entities['person'])){
                return \App\Models\Schedule::getScheduleOfTeacherAtParticularDay($entities['person']->entity, date('w',strtotime($entities['Day']->entity)));
              }
              else
                return auth()->user()->teacher->getScheduleByDay(date('w',strtotime($entities['Day']->entity)));
            }
            else{
              if(isset($entities['person'])){
                return auth()->user()->teacher->getTeacherSchedule($entities['person']->entity);
              }
              if(isset($entities['Group']) && isset($entities['Specialization'])){
                return \App\Models\Schedule::getScheduleByDayAndSpecialization(null,$entities['Specialization']->entity,$entities['Group']->entity);
              }
              else{
                return auth()->user()->teacher->getSchedule();
              }
            }
        }
      }
    }

}
