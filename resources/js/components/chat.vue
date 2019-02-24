<template>
    <div class="chat-wrapper py-4">
        <div id="chat">
            <h2 class="">Добрый день,<br/> <span>Nurdaulet</span></h2>
            <div class="msg-box" v-for="(message, index) in messages">
                <message :messageId="index"></message>
            </div>
        </div>
        <div id="bottom">
            <div class="commands">
                <span role="button" class="badge badge-pill command" v-for="item in commands" :command="item.value" @click="query">
                    {{ item.label }}
                </span>
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
                    {content: 'Как я могу помочь?', 'class': 'msg msg-left'},
                    {content: 'Уровень заинтересованности', 'class': 'msg msg-right'},
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
                axios.get('/api/query', {params: {content: command}}).then(response => {
                    if (response.data.result.status=='OK') {
                        this.messages.push(response.data.result);
                    }
                });
            }
        },

    }
</script>
