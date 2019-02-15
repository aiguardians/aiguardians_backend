<?php

use Illuminate\Database\Seeder;
use App\Models\Speciality;
use App\Models\Group;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use App\User;

class ScheduleParser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = [1, 2, 3, 4];
        $coursesLink = "http://schedule.iitu.kz/rest/user/get_specialty.php?course=";
        foreach($courses as $course) {
            $link = $coursesLink.$course;
            $specialities = $this->getJson($link)['result'];
            $this->saveSpecialities($specialities);
            foreach($specialities as $speciality) {
                $link = "http://schedule.iitu.kz/rest/user/get_group.php?course={$course}&specialty_id={$speciality['id']}";
                $groups = $this->getJson($link)['result'];
                $this->saveGroups($groups, $course, $speciality);
                foreach($groups as $group) {
                    $link = "http://schedule.iitu.kz/rest/user/get_timetable_block.php?block_id={$group['id']}";
                    $schedule = $this->getJson($link);
                    $this->parseSchedule($schedule, $group);
                }
            }
        }
    }

    private function saveCourses($courses, $group) {
        foreach($courses as $course_id=>$course) {
            if (Course::where('tmp_course_id', $course_id)->exists()) {
                continue;
            }
            $data = new Course;
            $data->name = $course['subject_en'];
            $data->group_id = Group::where('tmp_group_id', $group['id'])->first()->id;
            $data->tmp_course_id = $course_id;
            $data->save();
        }
        echo "Courses: OK!\n";
    }

    private function saveTeachers($schedule) {
        foreach($schedule['teachers'] as $teacher_id => $teacher) {
            if (Teacher::where('tmp_user_id', $teacher_id)->exists()) {
                continue;
            }
            $data = new User;
            $data->name = $teacher['teacher_en'];
            $data->save();
            $newTeacher = new Teacher;
            $newTeacher->user_id = $data->id;
            $newTeacher->tmp_user_id = $teacher_id;
            $newTeacher->save();
        }
        echo "Teachers: OK!\n";
    }

    private function saveSchedule($schedule, $group) {
        foreach($schedule['timetable'] as $day => $timetable) {
            foreach($timetable as $items) {
                foreach($items as $key => $item) {
                    if ($schedule['bundles'][$item['bundle_id']]['type']=='room') {
                        $room = $schedule['bundles'][$item['bundle_id']][0]['name_en'];
                    }
                    else {
                        $room = "";
                        foreach($schedule['bundles'][$item['bundle_id']]['name'] as $i=>$r) {
                            if ($i!=0) {
                                $room.=", ";
                            }
                            $room.=$r['name_en'];
                        }
                    }
                    $data = new Schedule;
                    $data->course_id = Course::where('tmp_course_id', $item['subject_id'])->first()->id;
                    $data->subject_type = $schedule['subject_types'][$item['subject_type_id']]['subject_type_en'];
                    $data->room = $room;
                    $data->day = $day;
                    $data->time = [
                        'start_time' => $schedule['times'][$item['time_id']]['start_time'],
                        'end_time' => $schedule['times'][$item['time_id']]['end_time'],
                    ];
                    $data->save();
                    $teacher = Teacher::where('tmp_user_id', $item['teacher_id'])->first();
                    $teacher->appointment = $schedule['appointments'][$item['appointment_id']]['appointment_en'];
                    $teacher->regalia = $schedule['regalias'][$item['regalia_id']]['regalia_en'];
                    $teacher->save();
                    $course = Course::where('tmp_course_id', $item['subject_id'])->first();
                    if ($item['subject_type_id']==1) {
                        $course->lecture_teacher_id = $teacher->id;
                    }
                    else if ($item['subject_type_id']==2) {
                        $course->lab_teacher_id = $teacher->id;
                    }
                    else {
                        $course->practice_teacher_id = $teacher->id;
                    }
                    $course->save();
                }
            }
        }
        echo "Schedule: OK!\n";
    }

    private function parseSchedule($schedule, $group) {
        $this->saveTeachers($schedule);
        $this->saveCourses($schedule['subjects'], $group);
        if (isset($schedule['timetable'])) {
            $this->saveSchedule($schedule, $group);
        }
    }

    private function saveGroups($groups, $course, $speciality) {
        foreach($groups as $group) {
            if (Group::where('tmp_group_id', $group['id'])->exists()) {
                continue;
            }
            $data = new Group;
            $data->name = $group['name_en'];
            $data->course = $course;
            $data->speciality_id = Speciality::where('tmp_speciality_id', $speciality['id'])->first()->id;
            $data->tmp_group_id = $group['id'];
            $data->save();
        }
        echo "Groups: OK!\n";
    }

    private function saveSpecialities($specialities) {
        foreach($specialities as $speciality) {
            if (Speciality::where('tmp_speciality_id', $speciality['id'])->exists()) {
                continue;
            }
            $data = new Speciality;
            $data->name = $speciality['name_en'];
            $data->short_name = $speciality['name_en'];
            $data->tmp_speciality_id = $speciality['id'];
            $data->save();
        }
        echo "Specialities: OK!\n";
    }

    private function getJson($link) {
        $res = file_get_contents($link);
        $json = json_decode($res, true);
        return $json;
    }

}
