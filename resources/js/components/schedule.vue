<template>
    <div class="schedule">
        <div v-for="(day, index) in days" class="schedule-day">
            <div class="d-flex justify-content-center"><h5>{{ day.name }}</h5></div>
            <div v-for="item in schedule[index+1]" :class="['schedule-box', {'current-lesson': item.time.current, 'next-lesson': item.time.next}]">
                <div class="d-flex justify-content-between schedule-course">
                    <span class="course-name">{{ item.course.name }}</span>
                    <span>{{ item.time.start_time }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>{{ group.name }}</span>
                    <span>{{ item.subject_type }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                days: [
                    {
                        name: 'Monday',
                    },
                    {
                        name: 'Tuesday',
                    },
                    {
                        name: 'Wednesday',
                    },
                    {
                        name: 'Thursday'
                    },
                    {
                        name: 'Friday',
                    },
                    {
                        name: 'Saturday',
                    },
                ],
                group: this.$parent.message.content.group,
                schedule: this.$parent.message.content.schedule,
            };
        },
        mounted: function() {

        },
    }
</script>
