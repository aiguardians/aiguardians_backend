<template>
    <div>
        <video autoplay class="d-none"></video>
        <canvas id="canvas" width="640" height="480" @click="takeScreenshot"></canvas>
        <img id="screenshot" class="" src=""/>
    </div>
</template>

<script>
    import { saveAs } from 'file-saver';
    export default {
        data: function() {
            return {
                video: null,
                ctx: null,
            };
        },
        mounted: function() {
            if (this.hasGetUserMedia()) {
                this.main();
            } else {
              alert('getUserMedia() is not supported by your browser');
            }
        },
        methods: {
            hasGetUserMedia: function () {
              return !!(navigator.mediaDevices &&
                navigator.mediaDevices.getUserMedia);
            },
            main: function() {
                this.video = document.querySelector('video');
                const constraints = {
                  video: true
                };
                navigator.mediaDevices.getUserMedia(constraints).
                then((stream) => {this.video.srcObject = stream});

                this.ctx = document.getElementById('canvas').getContext('2d');
                this.drawVideo();
            },
            drawVideo: function() {
                window.requestAnimationFrame(this.drawVideo);
                this.ctx.drawImage(this.video, 0, 0);
            },
            makeblob: function (dataURL) {
                var BASE64_MARKER = ';base64,';
                if (dataURL.indexOf(BASE64_MARKER) == -1) {
                    var parts = dataURL.split(',');
                    var contentType = parts[0].split(':')[1];
                    var raw = decodeURIComponent(parts[1]);
                    return new Blob([raw], { type: contentType });
                }
                var parts = dataURL.split(BASE64_MARKER);
                var contentType = parts[0].split(':')[1];
                var raw = window.atob(parts[1]);
                var rawLength = raw.length;

                var uInt8Array = new Uint8Array(rawLength);

                for (var i = 0; i < rawLength; ++i) {
                    uInt8Array[i] = raw.charCodeAt(i);
                }

                return new Blob([uInt8Array], { type: contentType });
            },
            takeScreenshot: function() {
                const img = document.querySelector('#screenshot');
                var data = this.ctx.canvas.toDataURL();
                img.src = data;
                var params = {
                    "returnFaceId": "true",
                    "returnFaceLandmarks": "true",
                };
                $.ajax({
                    url: "https://westus.api.cognitive.microsoft.com/face/v1.0/detect?" + $.param(params),
                    beforeSend: function(xhrObj) {
                        xhrObj.setRequestHeader("Content-Type","application/octet-stream");
                        xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key","200fd87c86524526aa0df29ccaa8badd");
                    },
                    type: "POST",
                    data: this.makeblob(data),
                    processData: false,
                })
                .done(function(data) {
                    alert("success");
                })
                .fail(function() {
                    alert("error");
                });
            },
        },
    }
</script>
