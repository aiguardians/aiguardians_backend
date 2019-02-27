<template>
    <div>
        <video autoplay class="d-none"></video>
        <canvas id="canvas" width="640" height="480"></canvas>
    </div>
</template>

<script>
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
                this.sendDetectionRequest("detect", JSON.stringify({"url": window.location.origin+self.students[i].image}), 'json')
                .done(function(data) {
                    console.log(data);
                    self.students[i].faceId = data[0].faceId;
                    self.students[i].cnt = 0;
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
                }, 7000);
            },
            drawVideo: function() {
                window.requestAnimationFrame(this.drawVideo);
                this.ctx.beginPath();
                this.ctx.drawImage(this.video, 0, 0);
                var self = this;
                this.students.forEach(function(f) {
                    if (!f.coords) return;
                    self.ctx.strokeStyle = "rgba(0, 255, 0, 0.7)";
                    self.ctx.rect(f.coords.left, f.coords.top, f.coords.width, f.coords.height);
                    self.ctx.font = "30px Arial";
                    self.ctx.fillText(f.first_name + ' ' + f.last_name, f.coords.left, f.coords.top);
                    self.ctx.stroke();
                });
                this.faces.forEach(function(f) {
                    if (f.checked) return;
                    self.ctx.strokeStyle = "rgba(255, 0, 0, 0.7)";
                    self.ctx.rect(f.left, f.top, f.width, f.height);
                    self.ctx.stroke();
                });
            },
            verifyFaces: function(faces) {
                console.log('Detected: ' + faces.length);
                var self = this;
                this.faces = [];
                for(var i = 0;i<faces.length;++i) {
                    this.faces.push(faces[i].faceRectangle);
                }
                // this.faces = faces.map(f => { return f.faceRectangle; });
                for(var i = 0;i<this.students.length;++i) {
                    this.students.coords = null;
                    for(var j = 0;j<faces.length;++j) {
                        if (this.faces[j].checked) {
                            break;
                        }
                        var s_id = i;
                        var f_id = j;
                        this.sendDetectionRequest('verify', JSON.stringify({faceId1: this.students[i].faceId, faceId2: faces[j].faceId}), 'json', {})
                        .done(function(data) {
                            if (data.isIdentical) {
                                console.log('identical');
                                self.students[s_id].cnt++;
                                self.students[s_id].coords = faces[f_id].faceRectangle;
                                self.faces[f_id].checked = true;
                            }
                            console.log(data);
                        }).fail(function(err) {
                            console.log(err);
                        });
                    }
                }
            },
            takeScreenshot: function() {
                console.log('screen shot');
                var self = this;
                this.sendDetectionRequest("detect", this.makeblob(this.ctx.canvas.toDataURL()), 'octet-stream')
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
            sendDetectionRequest: function(action, data, contentType, params={returnFaceId: "true", returnFaceLandmarks: "false"}) {
                return $.ajax({
                    url: "https://westus.api.cognitive.microsoft.com/face/v1.0/"+action+"?" + $.param(params),
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
