<template>
    <div class="schedule">
        <div v-for="(day, index) in days" :class="['schedule-day', {'d-none': schedule[index+1]==undefined}]">
            <div class="d-flex justify-content-center"><h5>{{ day.name }}</h5></div>
            <div v-for="(items, start_time) in schedule[index+1]" :class="['schedule-box', {'current-lesson': items[0].time.current, 'next-lesson': items[0].time.next}]">
                <div class="d-flex justify-content-between schedule-course">
                    <span class="course-name">{{ items[0].course.name }}</span>
                    <span>{{ start_time }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span v-for="item in items">{{ item.group.name }}</span>
                    <span>{{ items[0].subject_type }}</span>
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
                schedule: this.$parent.message.content,
            };
        },
        mounted: function() {

        },
    }
</script>
