<template>
    <div>
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
</template>

<script>

export default {
    data: function() {
        return {
            message: this.$parent.message,
            deadlines: [],
        };
    },
    mounted: function() {
        this.deadlines = this.message.content;
        console.log(this.message);
    },
    methods: {

    },
}

</script>
