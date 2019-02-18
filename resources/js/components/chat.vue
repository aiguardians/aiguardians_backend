<template>
    <div class="chat-wrapper">
        <div id="chat">
            <h2 class="">Добрый день,<br/> <span>Nurdaulet</span></h2>
            <!-- <img width="100%" src="/img/design/chat.png"/> -->

            <div class="msg-box">
                <div class="msg msg-left">
                    <span>Как я могу помочь?</span>
                </div>
            </div>
            <div class="msg-box">
                <div class="msg msg-right">
                    <span>Уровень заинтересованности</span>
                </div>
            </div>
        </div>
        <div id="bottom">
            <div id="commands">
                <span class="badge badge-pill command">Functions1</span>
                <span class="badge badge-pill command">Functions2</span>
                <span class="badge badge-pill command">Functions3</span>
                <span class="badge badge-pill command">Functions3</span>
                <span class="badge badge-pill command">Functions4</span>
                <span class="badge badge-pill command">Functions5</span>
            </div>
            <div id="micro-box">
                <canvas id="micro-background"></canvas>
                <button id="micro">
                    <div id="logo"></div>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
