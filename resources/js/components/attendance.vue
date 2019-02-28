<template>
<div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <h4 class="a-h">
                            My Courses
                        </h4>
                        <div v-for="course in courses" class="a-course" :courseid="course.id" @click="selectCourse">
                            {{ course.name }}
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="">
                            <div id="calendar" class=""></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="a-h">
                            Video Analysis
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>

import moment from 'moment';
export default {
    data: function() {
        return {
            courses: [],
            selectedday: null,
            selectedcourse: null,
        };
    },
    mounted: function() {
        axios.get('/courses').then(response => {
            this.courses = response.data;
            var element = document.getElementById("calendar");
            var myCalendar = jsCalendar.new(element);
            var self = this;
            myCalendar.onDateClick(function(event, date) {
                self.pickDate(moment(String(date)).format('DD-MM-YYYY'));
            });
        });
    },
    methods: {
        pickDate: function(date) {
            this.selectedday = date;
            if (!this.selectedcourse) {
                return;
            }
            axios.post('/attendance', {
                'date': this.date,
                'course': this.course,
            }).then(response => {
                console.log(response);
            });
        },
        selectCourse: function(e) {
            this.selectedcourse = e.target.getAttribute('courseid');
            if (!this.selectedday) {
                return;
            }
            axios.post('/attendance', {
                'date': this.date,
                'course': this.course,
            }).then(response => {
                console.log(response);
            });
        },
    },
}

</script>
