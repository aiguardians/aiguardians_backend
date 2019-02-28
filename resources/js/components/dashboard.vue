<template>
<div class="container-fluid py-4 dashboard">
    <div class="row">
        <div class="col-sm-4">
            <h4> Личная информация </h4>
            <div class="row">
                <div class="col-sm-6">
                    <img style="width:100%;border-radius: 5px;" :src="this.$props.userimg"/>
                    <div class="change-link"><a href="#" onclick="javascript:void(0);">Change photo</a></div>
                    <form id="avatar" enctype="multipart/form-data" class="d-none">
                        <input name="image" type="file"/>
                    </form>
                </div>
                <div class="col-sm-6">
                    <h6></h6>
                    <div><span>Role:</span>{{ this.$props.userrole }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h4>Today</h4>
                </div>
                <div>
                    <!-- <div v-for="item in schedule">
                        <div class="d-inline-block">
                            <div class="d-block">{{ item.time.start_time }}</div>
                            <div class="d-block">{{ item.time.end_time }}</div>
                        </div>
                        <div :class="['schedule-box', 'd-inline-block', {'current-lesson': item.time.current, 'next-lesson': item.time.next}]">
                            <div class="d-flex justify-content-between">
                                <span class="course-name-2">{{ item.course.name }}</span>
                                <span>{{ item.subject_type }}</span>
                            </div>
                            <div class="d-block">{{ item.course.group.name }}</div>
                        </div>
                    </div> -->
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
                <div v-for="(items, day) in deadlines">
                    <div>
                        <h6>
                            {{ day }}
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
    props: ['userimg', 'userrole'],
    data: function() {
        return {
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
}

</script>
