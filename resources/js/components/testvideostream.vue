<template>
    <div>
        <video autoplay class="d-none"></video>
        <canvas id="canvas" width="640" height="480"></canvas>
    </div>
</template>

<script>
    import { saveAs } from 'file-saver';
    export default {
        props: ['groupid'],
        data: function() {
            return {
                video: null,
                ctx: null,
                faces: [],
                group: null,
                students: [],
            };
        },
        mounted: function() {
            this.getStudents();

            if (this.hasGetUserMedia()) {
                this.main();
            } else {
              alert('getUserMedia() is not supported by your browser');
            }
        },
        methods: {
            getStudents: function() {
                var self = this;
                axios.get('/api/group/' + this.$props.groupid + '/students')
                .then(response => {
                    self.students = response.data.students;
                    for(var i=0;i<self.students.length;++i) {
                        self.detectStudent(i);
                    }
                    self.group = response.data.group;
                });
            },
            detectStudent: function(i) {
                var self = this;
                this.sendDetectionRequest(JSON.stringify({"url": window.location.origin+self.students[i].image}), 'json')
                .done(function(data) {
                    console.log(data);
                    self.students[i].faceId = data[0].faceId;
                }).fail(function() {
                    console.log("error");
                });
            },
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
                var self = this;
                window.setInterval(function() {
                    self.takeScreenshot();
                }, 5000);
            },
            drawVideo: function() {
                window.requestAnimationFrame(this.drawVideo);
                this.ctx.beginPath();
                this.ctx.drawImage(this.video, 0, 0);
                var self = this;
                this.faces.forEach(function(f) {
                    self.ctx.strokeStyle = "rgba(255, 0, 0, 0.7)";
                    self.ctx.rect(f.left, f.top, f.width, f.height);
                    self.ctx.stroke();
                });
            },
            verifyFaces: function(data) {
                console.log('Detected: ' + data.length);
                this.faces = [];

                var self = this;
                data.forEach(function(face) {
                    self.drawFaceRectangle(face.faceRectangle);
                });
            },
            drawFaceRectangle: function(f) {
                this.faces.push(f);
            },
            takeScreenshot: function() {
                console.log('screen shot');
                var self = this;
                this.sendDetectionRequest(this.makeblob(this.ctx.canvas.toDataURL()), 'octet-stream')
                .done(function(data) {
                    self.verifyFaces(data);
                }).fail(function() {
                    console.log("error");
                });
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
            sendDetectionRequest: function(data, contentType) {
                var params = {
                    "returnFaceId": "true",
                    "returnFaceLandmarks": "false",
                };
                return $.ajax({
                    url: "https://westus.api.cognitive.microsoft.com/face/v1.0/detect?" + $.param(params),
                    beforeSend: function(xhrObj) {
                        xhrObj.setRequestHeader("Content-Type","application/"+contentType);
                        xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key","200fd87c86524526aa0df29ccaa8badd");
                    },
                    type: "POST",
                    data: data,
                    processData: false,
                });
            }
        },
    }
</script>
