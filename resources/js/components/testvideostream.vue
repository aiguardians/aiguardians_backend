<template>
    <div>
        <video autoplay></video>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {

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
                const constraints = {
                    video: true
                };

                const video = document.querySelector('video');

                navigator.mediaDevices.getUserMedia(constraints).
                then((stream) => {video.srcObject = stream});
            }
        },
    }
</script>
