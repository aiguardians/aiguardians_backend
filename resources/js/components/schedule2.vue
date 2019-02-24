<template>
    <div class="s-box">
        <div class="d-inline-block s-schedule-box">
            <div class="d-block s-top-nav">
                <div class="d-inline-block">
                    <h5 class="d-inline-block">{{ days[currentDay-1].name }}</h5>
                </div>
            </div>
            <div>
                <div v-for="item in schedule[currentDay]">
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
                </div>
            </div>
        </div>
        <div class="d-inline-block s-right-content s-schedule-box">
            <div class="d-inline-block m-scroll-nav">
                <span role="button" v-for="(day, index) in days" :class="['badge', 'badge-pill', 'command', {'s-active': (index+1==currentDay)}]" :day="index+1" @click="dayClick">
                    {{ day.name }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['groupid'],
        data: function() {
            return {
                days: [
                    {
                        name: 'Понедельник',
                    },
                    {
                        name: 'Вторник',
                    },
                    {
                        name: 'Среда',
                    },
                    {
                        name: 'Четверг'
                    },
                    {
                        name: 'Пятница',
                    },
                    {
                        name: 'Суббота',
                    },
                ],
                schedule: [],
                currentDay: 1,
                clickedCourse: null,
            };
        },
        mounted: function() {
            axios.get('/api/group/'+this.$props.groupid+'/schedule').then(response => {
                this.schedule = response.data.result;
                this.currentDay = response.data.currentDay;
            });
        },
        methods: {
            dayClick: function(e) {
                this.currentDay = e.target.getAttribute('day');
            }
        },
    }
</script>
