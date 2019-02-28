<template>
<div class="container-fluid py-4 dashboard">
    <div class="row">
        <div class="col-sm-4">
            <h4> Личная информация </h4>
            <div class="row">
                <div class="col-6 col-sm-12 col-md-12 col-lg-6">
                    <img id="userimage" class="n-u-img" :src="this.$props.userimg"/>
                    <div class="change-link"><a href="#" role="button" @click="setImageClicked"><span class="n-svg"><img src="/img/edit.svg"/></span>Change photo</a></div>
                    <input id="usrimg" class="d-none" @change="sendImage" name="image" type="file" accept="image/*"/>
                </div>
                <div class="col-6 col-sm-12 col-md-12 col-lg-6">
                    <h6>{{ username }}</h6>
                    <div><span>Role:</span>{{ this.$props.userrole }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h4>Today</h4>
                </div>
                <div class="col-sm-12">
                    <div v-for="(item, key) in schedule">
                        <div class="d-inline-block d-color-black">
                            <div class="d-block">{{ item[0].time.start_time }}</div>
                            <div class="d-block">{{ item[0].time.end_time }}</div>
                        </div>
                        <div :class="['schedule-box', 'd-inline-block', {'current-lesson': item[0].time.current, 'next-lesson': item[0].time.next}]">
                            <div class="d-flex justify-content-between">
                                <span class="course-name-2 d-color-black">{{ item[0].course.name }}</span>
                                <span>{{ item[0].subject_type }}</span>
                            </div>
                            <div class="d-block">{{ item[0].course.group.name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <h4>My courses</h4>
            <div class="d-course-box">
                <div v-for="course in courses">
                    <div class="d-course">
                        <div class="d-color-black">
                            {{ course.name }}
                        </div>
                        <div>
                            {{ course.group.name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <h4>Deadlines</h4>
            <div class="d-deadline-box">
                <div v-for="(items, day) in deadlines" class="d-day">
                    <div>
                        <h6>
                            {{ items[0].deadline }}
                            <span v-if="items[0].isToday">Today</span>
                        </h6>
                    </div>
                    <div v-for=" item in items" class="d-deadline">
                        <div class="d-inline-block">
                            <div class="d-color-black">{{ item.name }}</div>
                            <div class="d-color-black">{{ item.course.name }}</div>
                        </div>
                        <div class="d-inline-block d-v-top">
                            <div :class="['d-v-right', item.class]">
                                осталось {{ item.remaining }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>

export default {
    props: ['userimg', 'userrole', 'username'],
    data: function() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            schedule: [],
            courses: [],
            deadlines: [],
        };
    },
    mounted: function() {
        axios.get('/query?content=Give+me+schedule+for+today').then(response => {
            this.schedule = response.data.result.content;
            console.log(this.schedule);
        });
        axios.get('/courses').then(response => {
            this.courses = response.data;
        });
        axios.get('/deadlines').then(response => {
            this.deadlines = response.data;
        });
    },
    methods: {
        setImageClicked: function() {
            let file = document.getElementById('usrimg');
            file.click();
        },
        sendImage: function() {
            let file = document.getElementById('usrimg');
            let formData = new FormData();
            formData.append('image', file.files[0]);
            axios.post('/set/image', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                let fr = new FileReader();
                fr.onload = function () {
                     document.getElementById('userimage').src = fr.result;
                 }
                 fr.readAsDataURL(file.files[0]);
            });
        },
    },
}

</script>
