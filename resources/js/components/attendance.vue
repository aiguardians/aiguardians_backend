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
                        <div v-for="course in courses" :class="['a-course', {'a-active': selectedcourse==course.id}]" :courseid="course.id" @click="selectCourse">
                            {{ course.name }}
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="">
                            <div id="calendar" class=""></div>
                        </div>
                        <div>
                            <div v-for="(group, name) in groups">
                                {{ name }}
                                <div>
                                    <div v-for="s in group">
                                        {{ s.student.first_name }}
                                    </div>
                                </div>
                            </div>
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
                        <div>
                            <video  id="video" src="" controls></video>
                        </div>
                        <div>
                            <canvas id="canvas"></canvas>
                        </div>
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
            groups: [],
            video: [],
            res: [],
            canvasData: [],
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
        axios.get('/video').then(response => {
            let v = document.getElementById('video');
            v.src = response.data;
            console.log(response.data);
        });
        axios.get('/emotions').then(response => {
            this.res = response.data;
            this.drawCanvas();
        });
    },
    methods: {
        pickDate: function(date) {
            this.selectedday = date;
            if (!this.selectedcourse) {
                return;
            }
            axios.post('/attendance', {
                'date': this.selectedday,
                'course': this.selectedcourse,
            }).then(response => {
                this.groups = response.data;
                console.log(this.groups);
            });
        },
        selectCourse: function(e) {
            this.selectedcourse = e.target.getAttribute('courseid');
            if (!this.selectedday) {
                return;
            }
            axios.post('/attendance', {
                'date': this.selectedday,
                'course': this.selectedcourse,
            }).then(response => {
                this.groups = response.data;
                console.log(this.groups);
            });
        },
        drawCanvas: function() {
            let maxv = 0;
            let self = this;
            let duration = this.res[this.res.length-1].time;
            this.res.forEach(function(item) {
                maxv = Math.max(item.detected, maxv);
            });
            this.res.forEach(function(tmp) {
                let lowc = 0;
                let highc = 0;
                let medc = 0;
                tmp.emotions.forEach(function(item) {
                    if (item.yaw<-12 || item.yaw>12) {
                        medc++;
                    }
                    else {
                        highc++;
                    }
                    lowc+=(maxv-item.detected);
                });
                self.canvasData.push(100*(highc*1+medc*0.5)/maxv);
            });
            let video = document.getElementById('video');
            let ctx = document.getElementById('canvas').getContext('2d');
            let width = ctx.canvas.width-20;
            let height = ctx.canvas.height-10;
            let len = width/duration;
            ctx.beginPath();
            ctx.strokeStyle="purple";
            ctx.moveTo(10, height);
            console.log(self.canvasData);
            for(let i =0;i<self.canvasData.length;++i) {
                console.log(10+(1+i)*len, height-this.canvasData[i]*height/100);
                // ctx.lineTo((10+i)*len, 10+self.canvasData[i]*height);
                ctx.lineTo(10+(1+i)*len, height-this.canvasData[i]*height/100);
            }
            ctx.lineTo(width, height);
            ctx.stroke();
            ctx.beginPath();
            ctx.strokeStyle="lightgrey";
            ctx.moveTo(0, height+10);
            ctx.lineTo(width+20, height+10);
            ctx.stroke();
        },
    },
}

</script>
