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
                    {label: 'Schedule', value: 'Give me the schedule'},
                    {label: 'Function1', value: 'Function1'},
                    {label: 'Function1', value: 'Function1'},
                    {label: 'Function1', value: 'Function1'},
                    {label: 'Function1', value: 'Function1'},
                ],
            };
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            query: function(e) {
                var command = e.target.getAttribute('command');
                this.messages.push({content: command, class: 'msg msg-right', component: 'default'});
                this.$nextTick(function () {
                    console.log(document.getElementById("chat").lastChild);
                    document.getElementById("chat").lastChild.scrollIntoView({behavior: 'smooth'});
                });
                axios.get('/api/query', {params: {content: command}}).then(response => {
                    if (response.data.result.status=='OK') {
                        this.messages.push(response.data.result);
                    }
                });
            }
        },

    }
</script>
