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
      $arr = [];
      return 'need attendance';
    }

    public static function GetDeadlines($entities){
      $arr = [];
      return 'need deadlines';
    }

    public static function GetEmotions($entities){
        $arr = [];
        return 'need emotions';
    }


    public static function GetInformation($entities){
      $arr = [];
      return 'need deadlines';
    }

    public static function GetRecommendations($entities){
      $arr = [];
      return 'need recommendations';
    }

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
              return auth()->user()->student->getScheduleByDayAndSpecialization(null,$entities['Specialization']->entity,$entities['Group']->entity);
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
                // return $entities['person']->entity;
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
