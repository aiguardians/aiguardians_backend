<template>
    <div id="micro-box">
        <canvas id="micro-background"></canvas>
        <button id="micro" @click="activate" :disabled="speechConfig == null">
            <div id="logo"></div>
        </button>
    </div>
</template>

<script>
    var sdk = require("microsoft-cognitiveservices-speech-sdk")
    export default {
        data: function() {
            return {
                audioConfig: sdk.AudioConfig.fromDefaultMicrophoneInput(),
                speechConfig: null,
                isRecognitionStarted: false,
            };
        },
        mounted: function() {
            axios.get('/api/token').then(response => {
                var token = JSON.parse(atob(response.data.split(".")[1]));
                this.speechConfig = sdk.SpeechConfig.fromAuthorizationToken(response.data, token.region);
            });
        },
        methods: {
            activate: function() {
                try {
                    this.isRecognitionStarted = true;
                    this.visualize();
                    var self = this;
                    var recognizer = new sdk.SpeechRecognizer(this.speechConfig, this.audioConfig);
                    recognizer.recognizeOnceAsync(
                        function (result) {
                            if (result.privText && result.privText.length>0) {
                                self.$parent.getResult(result.privText);
                            }
                            self.isRecognitionStarted = false;
                            recognizer.close();
                        },
                        function (err) {
                            self.isRecognitionStarted = false;
                            alert(err);
                            recognizer.close();
                        }
                    );
                } catch(err) {
                    alert(err);
                }
            },
            visualize: function() {
                var self = this;
                window.AudioContext = window.AudioContext || window.webkitAudioContext;
                var audioCtx = new AudioContext();
                navigator.mediaDevices.getUserMedia({audio:true})
                .then(function(stream) {
                    animate(stream);
                    function animate(stream) {
                        var canvas = document.getElementById('micro-background');
                        var ctx = canvas.getContext('2d');

                        var mediaRecorder = new MediaRecorder(stream);
                        var source = audioCtx.createMediaStreamSource(stream);
                        var analyser = audioCtx.createAnalyser();
                        analyser.fftSize = 2048;
                        var bufferLength = analyser.frequencyBinCount;
                        var dataArray = new Uint8Array(bufferLength);
                        source.connect(analyser);
                        draw();
                        function draw() {
                            var WIDTH = canvas.width;
                            var HEIGHT = canvas.height;
                            if (self.isRecognitionStarted == false) {
                                stream.getAudioTracks().forEach(function(track){track.stop();});
                                ctx.clearRect(0, 0, WIDTH, HEIGHT);
                                return;
                            }
                            requestAnimationFrame(draw);
                            analyser.getByteTimeDomainData(dataArray);
                            ctx.fillStyle = 'rgba(255, 255, 255, 0.5)';
                            ctx.fillRect(0, 0, WIDTH, HEIGHT);

                            ctx.lineWidth = 1;
                            ctx.strokeStyle = 'blue';

                            ctx.beginPath();

                            var sliceWidth = WIDTH * 1.0 / bufferLength;
                            var x = 0;

                            for(var i = 0; i < bufferLength; i++) {

                              var v = dataArray[i] / 128.0;
                              var y = v * HEIGHT/2;
                              if(i === 0) {
                                ctx.moveTo(x, y);
                              } else {
                                ctx.lineTo(x, y);
                                ctx.lineTo(x, HEIGHT/2);
                                ctx.lineTo(x, y);
                              }

                              x += sliceWidth;
                            }

                            ctx.lineTo(canvas.width, canvas.height/2);
                            ctx.stroke();
                        }
                    }
                }).catch(function(e) {
                    alert(e);
                });
            }
        }
    }
</script>
