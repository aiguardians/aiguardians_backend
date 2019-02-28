<template>
    <div class="chat-wrapper">
        <div id="chat">
            <div class="msg-box" v-for="(message, index) in messages">
                <message :messageId="index"></message>
            </div>
        </div>
        <div id="bottom">
            <div>
                <div class="commands">
                    <span role="button" class="badge badge-pill command" v-for="item in commands" :command="item.value" @click="query">
                        {{ item.label }}
                    </span>
                </div>
            </div>
            <micro-box></micro-box>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                messages: [
                    {content: 'Welcome back!', 'class': 'msg msg-left'},
                    {content: 'How can i help you?', 'class': 'msg msg-left'},
                ],
                commands: [
                    {label: 'Get Schedule', value: 'Give me the schedule'},
                    {label: 'Deadlines', value: 'Show me deadlines'},
                    {label: 'SIS-1811', value: 'Show me schedule of sis 1811 for today?'},
                    {label: 'Lessons of Mr. William', value: 'Show me schedule of William on Tuesday'},
                    {label: 'Attendance', value: 'check attendance'},
                ],
            };
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            query: function(e) {
                var command = e.target.getAttribute('command');
                this.getResult(command);
            },
            getResult: function(command) {
                this.messages.push({content: command, class: 'msg msg-right', component: 'default'});
                this.$nextTick(function () {
                    document.getElementById("chat").lastChild.scrollIntoView({behavior: 'smooth'});
                });
                axios.get('/query', {params: {content: command}}).then(response => {
                    if (response.data.result.status=='OK') {
                        this.messages.push(response.data.result);
                    }
                });
            }
        },

    }
</script>
