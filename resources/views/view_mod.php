<!DOCTYPE html>
<html>

<head>
    <meta charset=utf-8 />
    <title>View - <?php echo $mod->nickname ?></title>


    <link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/7.17.0/video.min.js"></script>
    <!-- <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script> -->

</head>

<body>

    <div id="instructions">

        <video id="my_video_1" class="video-js vjs-default-skin vjs-fluid" width="640px" height="267px" controls preload="none" poster='<?php echo ($mod->platform == 'STR') ? 'https://cdn.strpst.com/assets/icons/metaogimage.jpg' : 'https://www.modelajewebcam.com/wp-content/uploads/2020/10/chaturbate.png' ?>' data-setup='{ "liveui": true, "aspectRatio":"640:267", "playbackRates": [1, 1.5, 2] }'>
            <source src="<?php echo $mod->stream ?>" type="application/x-mpegURL">
        </video>

        <style>
            #instructions {
                max-width: 640px;
                text-align: left;
                margin: 30px auto;
            }
        </style>

        <script>
            var player = videojs('my_video_1', {liveui: true});
            player.play();
        </script>

</body>

</html>
