<template>
    <div :class="[classes]">
        <schedule v-if="message.component=='schedule'"></schedule>
        <span v-else>{{ message.content }}</span>
    </div>
</template>

<script>
    export default {
        props: ['message-id'],
        data: function() {
            return {
                message: this.$parent.messages[this.messageId],
                classes: this.$parent.messages[this.messageId].class
            };
        },
        mounted: function() {

        },
    }
</script>
