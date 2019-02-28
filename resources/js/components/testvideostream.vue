<template>
    <div>
        <video autoplay class="d-none"></video>
        <canvas id="canvas" width="640" height="480"></canvas>
        <button class="btn btn-primary" @click="stopRecording">Stop</button>
    </div>
</template>

<script>
    export default {
        props: ['groupid'],
        data: function() {
            return {
                video: null,
                file: null,
                ctx: null,
                faces: [],
                group: null,
                students: [],
                recorder: null,
            };
        },
        mounted: function() {
            this.getStudents().then(response => {
                this.students = response.data.students;
                this.group = response.data.group;
                let p = Promise.resolve();
                for (let i = 0; i < this.students.length; i++) {
                    p = p.then(_ => new Promise(resolve => {
                        this.detectStudent(i).then(data => {
                            this.students[i].faceId = data[0].faceId;
                            this.students[i].cnt = 0;
                            setTimeout(function() {
                                resolve();
                            }, 0);
                        });
                    }));
                }
                p.then(_ => {
                    this.main();
                });
            });
        },
        methods: {
            stopRecording: function() {
                this.recorder.ondataavailable = e => {
                    this.file = new File([e.data], 'test.webm', {type: 'video/webm'});
                    let formData = new FormData();
                    formData.append('file', this.file);
                    axios.post( '/test/video', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function() {
                      console.log('SUCCESS!!');
                    });
                };
                this.recorder.stop();
            },
            detectStudent: function(i) {
                return this.sendDetectionRequest("detect", JSON.stringify({"url": "https://testaiguardians.azurewebsites.net" + this.students[i].image}), 'json');
            },
            getStudents: function() {
                return axios.get('/api/group/' + this.$props.groupid + '/students');
            },

            main: function() {
                if (this.hasGetUserMedia()) {
                    this.ctx = document.getElementById('canvas').getContext('2d');
                    this.video = document.querySelector('video');
                    const constraints = {
                        video: true
                    };
                    navigator.mediaDevices.getUserMedia(constraints)
                    .then((stream) => {
                        this.recorder = new MediaRecorder(stream, {
                          mimeType: 'video/webm'
                        });
                        this.recorder.start();
                        this.video.srcObject = stream;
                        this.drawVideo();
                        this.runDetection();
                    });
                } else {
                  alert('getUserMedia() is not supported by your browser');
                }
            },
            runDetection: function() {
                let self = this;
                this.detectFaces().then(data => {
                    this.faces = data;
                    this.verifyFaces()
                    .then(function() {
                        setTimeout(function() {
                            self.runDetection();
                        }, 0);
                    });
                });
            },
            verifyFaces: function() {
                console.log('Detected: ' + this.faces.length);
                let p = Promise.resolve();
                for (let i=0;i<this.students.length;++i) {
                    this.students[i].coords = null;
                    for (let j=0;j<this.faces.length;++j) {
                        if (this.faces[j].checked) {
                            continue;
                        }
                        p = p.then(_ => new Promise(resolve => {
                            if (this.faces[j].checked || this.students[i].coords) {
                                resolve();
                                console.log('continue');
                            }
                            else {
                                this.verifyFace(i, j).then(data => {
                                    if (data.isIdentical) {
                                        this.students[i].cnt++;
                                        this.students[i].coords = this.faces[j].faceRectangle;
                                        this.faces[j].checked = true;
                                        console.log("Detected: " + this.students[i].first_name);
                                    }
                                    setTimeout(function() {
                                        resolve();
                                    }, 0);
                                });
                            }
                        }));
                    }
                }
                return p;
            },
            verifyFace: function(i, j) {
                return this.sendDetectionRequest('verify', JSON.stringify({faceId1: this.students[i].faceId, faceId2: this.faces[j].faceId}), 'json', {});
            },
            detectFaces: function() {
                console.log('screen shot');
                return this.sendDetectionRequest("detect", this.makeblob(this.ctx.canvas.toDataURL()), 'octet-stream');
            },
            drawVideo: function() {
                window.requestAnimationFrame(this.drawVideo);
                this.ctx.drawImage(this.video, 0, 0);
                this.drawDetectedStudents();
                this.drawOtherStudents();
            },
            drawDetectedStudents: function() {
                this.ctx.beginPath();
                this.ctx.font = "20px Arial";
                this.ctx.strokeStyle = "rgba(0, 255, 0, 0.7)";
                this.ctx.fillStyle = "rgba(0, 255, 0, 0.7)";
                for (let i=0;i<this.students.length;++i) {
                    if (!this.students[i].coords) continue;
                    let coords = this.students[i].coords;
                    this.ctx.rect(coords.left, coords.top, coords.width, coords.height);
                    this.ctx.fillText(this.students[i].first_name + ' ' + this.students[i].last_name, coords.left, coords.top-5);
                }
                this.ctx.stroke();
            },
            drawOtherStudents: function() {
                this.ctx.beginPath();
                this.ctx.strokeStyle = "rgba(255, 0, 0, 0.7)";
                this.ctx.fillStyle = "rgba(255, 0, 0, 0.7)";
                for (let i=0;i<this.faces.length;++i) {
                    if (this.faces[i].checked) continue;
                    let coords = this.faces[i].faceRectangle;
                    this.ctx.rect(coords.left, coords.top, coords.width, coords.height);
                }
                this.ctx.stroke();
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
            hasGetUserMedia: function () {
              return !!(navigator.mediaDevices &&
                navigator.mediaDevices.getUserMedia);
            },
        },
    }
</script>
