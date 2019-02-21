<template>
    <div class="chat-wrapper">
        <div id="chat">
            <h2 class="">Добрый день,<br/> <span>Nurdaulet</span></h2>
            <div class="msg-box" v-for="(message, index) in messages">
                <message :messageId="index"></message>
            </div>
        </div>
        <div id="bottom">
            <div id="commands">
                <span class="badge badge-pill command" v-for="item in commands">
                    {{ item }}
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
                    'Functions1', 'Functions1', 'Functions1', 'Functions1', 'Functions1'
                ],
            };
        },
        mounted() {
            console.log('Component mounted.')
        }

    }
</script>
