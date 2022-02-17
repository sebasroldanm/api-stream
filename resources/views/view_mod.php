<!DOCTYPE html>
<html>

<head>
    <meta charset=utf-8 />
    <title>View - <?php echo $mod->nickname ?></title>


    <link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/7.17.0/video.min.js"></script>

</head>

<body>

    <div id="instructions">

        <video id="my_video_1"
            class="video-js vjs-default-skin"
            width="740px" height="420px"
            controls preload="none"
            data-setup='{}'>
        </video>

        <script>
            var player = videojs('my_video_1', {
                liveui: true
            });
            player.muted('true');
            player.src({
                type: "application/x-mpegURL",
                src: "<?php echo $mod->stream ?>",
            });
            player.poster('<?php echo ($mod->platform == 'STR') ? 'https://cdn.strpst.com/assets/icons/metaogimage.jpg' : 'https://www.modelajewebcam.com/wp-content/uploads/2020/10/chaturbate.png' ?>');
            player.play();
        </script>

        <style>
            #instructions {
                margin: auto 10%;
                display: flex;
                justify-content: center;
            }

            .video-js .vjs-control-bar {
                display: -webkit-box;
                display: -webkit-flex;
                display: -ms-flexbox;
                display: flex;
            }

            video-js .vjs-chromecast-button:before {
                font-size: 16px;
            }

            .vjs-live-display {
                cursor: pointer
            }

            /* PLAYER SKIN */
            .video-js .vjs-menu-button-inline.vjs-slider-active,
            .video-js .vjs-menu-button-inline:focus,
            .video-js .vjs-menu-button-inline:hover,
            .video-js.vjs-no-flex .vjs-menu-button-inline {
                width: 10em
            }

            .video-js .vjs-controls-disabled .vjs-big-play-button {
                display: none !important
            }

            .video-js .vjs-control {
                width: 3em
            }

            .video-js .vjs-menu-button-inline:before {
                width: 1.5em
            }

            .vjs-menu-button-inline .vjs-menu {
                left: 3em
            }

            .video-js.vjs-paused .vjs-big-play-button,
            .vjs-paused.vjs-has-started.video-js .vjs-big-play-button {
                display: block
            }

            .video-js .vjs-load-progress div,
            .vjs-seeking .vjs-big-play-button,
            .vjs-waiting .vjs-big-play-button {
                display: none !important
            }

            .video-js .vjs-mouse-display:after,
            .video-js .vjs-play-progress:after {
                padding: 0 .4em .3em
            }

            .video-js.vjs-ended .vjs-loading-spinner {
                display: none
            }

            .video-js.vjs-ended .vjs-big-play-button {
                display: block !important
            }

            .video-js *,
            .video-js:after,
            .video-js:before {
                box-sizing: inherit;
                font-size: inherit;
                color: inherit;
                line-height: inherit
            }

            .video-js.vjs-fullscreen,
            .video-js.vjs-fullscreen .vjs-tech {
                width: 100% !important;
                height: 100% !important
            }

            .video-js {
                font-size: 14px;
                overflow: hidden
            }

            .video-js .vjs-control {
                color: inherit
            }

            .video-js .vjs-menu-button-inline:hover,
            .video-js.vjs-no-flex .vjs-menu-button-inline {
                width: 8.35em
            }

            .video-js .vjs-volume-menu-button.vjs-volume-menu-button-horizontal:hover .vjs-menu .vjs-menu-content {
                height: 3em;
                width: 6.35em
            }

            .video-js .vjs-spacer,
            .video-js .vjs-time-control {
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
                -webkit-box-flex: 1 1 auto;
                -moz-box-flex: 1 1 auto;
                -webkit-flex: 1 1 auto;
                -ms-flex: 1 1 auto;
                flex: 1 1 auto
            }

            .video-js .vjs-time-control {
                -webkit-box-flex: 0 1 auto;
                -moz-box-flex: 0 1 auto;
                -webkit-flex: 0 1 auto;
                -ms-flex: 0 1 auto;
                flex: 0 1 auto;
                width: auto
            }

            .video-js .vjs-time-control.vjs-time-divider {
                width: 14px
            }

            .video-js .vjs-time-control.vjs-time-divider div {
                width: 100%;
                text-align: center
            }

            .video-js .vjs-time-control.vjs-current-time {
                margin-left: 1em
            }

            .video-js .vjs-time-control .vjs-current-time-display,
            .video-js .vjs-time-control .vjs-duration-display {
                width: 100%
            }

            .video-js .vjs-time-control .vjs-current-time-display {
                text-align: right
            }

            .video-js .vjs-time-control .vjs-duration-display {
                text-align: left
            }

            .video-js .vjs-play-progress:before,
            .video-js .vjs-progress-control .vjs-play-progress:before,
            .video-js .vjs-remaining-time,
            .video-js .vjs-volume-level:after,
            .video-js .vjs-volume-level:before,
            .video-js.vjs-live .vjs-time-control.vjs-current-time,
            .video-js.vjs-live .vjs-time-control.vjs-duration,
            .video-js.vjs-live .vjs-time-control.vjs-time-divider,
            .video-js.vjs-no-flex .vjs-time-control.vjs-remaining-time {
                display: none
            }

            .video-js.vjs-no-flex .vjs-time-control {
                display: table-cell;
                width: 4em
            }

            .video-js .vjs-progress-control {
                position: absolute;
                left: 0;
                right: 0;
                width: 100%;
                height: .5em;
                top: -.5em
            }

            .video-js .vjs-progress-control .vjs-load-progress,
            .video-js .vjs-progress-control .vjs-play-progress,
            .video-js .vjs-progress-control .vjs-progress-holder {
                height: 100%
            }

            .video-js .vjs-progress-control .vjs-progress-holder {
                margin: 0
            }

            .video-js .vjs-progress-control:hover {
                height: 1.5em;
                top: -1.5em
            }

            .video-js .vjs-control-bar {
                -webkit-transition: -webkit-transform .1s ease 0s;
                -moz-transition: -moz-transform .1s ease 0s;
                -ms-transition: -ms-transform .1s ease 0s;
                -o-transition: -o-transform .1s ease 0s;
                transition: transform .1s ease 0s
            }

            .video-js.not-hover.vjs-has-started.vjs-paused.vjs-user-active .vjs-control-bar,
            .video-js.not-hover.vjs-has-started.vjs-paused.vjs-user-inactive .vjs-control-bar,
            .video-js.not-hover.vjs-has-started.vjs-playing.vjs-user-active .vjs-control-bar,
            .video-js.not-hover.vjs-has-started.vjs-playing.vjs-user-inactive .vjs-control-bar,
            .video-js.vjs-has-started.vjs-playing.vjs-user-inactive .vjs-control-bar {
                visibility: visible;
                opacity: 1;
                -webkit-backface-visibility: hidden;
                -webkit-transform: translateY(3em);
                -moz-transform: translateY(3em);
                -ms-transform: translateY(3em);
                -o-transform: translateY(3em);
                transform: translateY(3em);
                -webkit-transition: -webkit-transform 1s ease 0s;
                -moz-transition: -moz-transform 1s ease 0s;
                -ms-transition: -ms-transform 1s ease 0s;
                -o-transition: -o-transform 1s ease 0s;
                transition: transform 1s ease 0s
            }

            .video-js.not-hover.vjs-has-started.vjs-paused.vjs-user-active .vjs-progress-control,
            .video-js.not-hover.vjs-has-started.vjs-paused.vjs-user-inactive .vjs-progress-control,
            .video-js.not-hover.vjs-has-started.vjs-playing.vjs-user-active .vjs-progress-control,
            .video-js.not-hover.vjs-has-started.vjs-playing.vjs-user-inactive .vjs-progress-control,
            .video-js.vjs-has-started.vjs-playing.vjs-user-inactive .vjs-progress-control {
                height: .25em;
                top: -.25em;
                pointer-events: none;
                -webkit-transition: height 1s, top 1s;
                -moz-transition: height 1s, top 1s;
                -ms-transition: height 1s, top 1s;
                -o-transition: height 1s, top 1s;
                transition: height 1s, top 1s
            }

            .video-js.not-hover.vjs-has-started.vjs-paused.vjs-user-active.vjs-fullscreen .vjs-progress-control,
            .video-js.not-hover.vjs-has-started.vjs-paused.vjs-user-inactive.vjs-fullscreen .vjs-progress-control,
            .video-js.not-hover.vjs-has-started.vjs-playing.vjs-user-active.vjs-fullscreen .vjs-progress-control,
            .video-js.not-hover.vjs-has-started.vjs-playing.vjs-user-inactive.vjs-fullscreen .vjs-progress-control,
            .video-js.vjs-has-started.vjs-playing.vjs-user-inactive.vjs-fullscreen .vjs-progress-control {
                opacity: 0;
                -webkit-transition: opacity 1s ease 1s;
                -moz-transition: opacity 1s ease 1s;
                -ms-transition: opacity 1s ease 1s;
                -o-transition: opacity 1s ease 1s;
                transition: opacity 1s ease 1s
            }

            .video-js.vjs-live .vjs-live-control {
                margin-left: 1em
            }

            .video-js .vjs-big-play-button {
                top: 50%;
                left: 50%;
                margin-left: -1em;
                width: 2em;
                border: none;
                color: #fff;
                -webkit-transition: border-color .4s, outline .4s, background-color .4s;
                -moz-transition: border-color .4s, outline .4s, background-color .4s;
                -ms-transition: border-color .4s, outline .4s, background-color .4s;
                -o-transition: border-color .4s, outline .4s, background-color .4s;
                transition: border-color .4s, outline .4s, background-color .4s;
                background-color: rgba(0, 0, 0, 0);
                font-size: 8em;
                border-radius: 0%;
                height: 1em !important;
                line-height: 1em !important;
                margin-top: -.5em !important
            }

            .video-js .vjs-menu-button-popup .vjs-menu {
                left: -3em
            }

            .video-js .vjs-menu-button-popup .vjs-menu .vjs-menu-content {
                background-color: transparent;
                width: 12em;
                left: -1.5em;
                padding-bottom: .5em
            }

            .video-js .vjs-menu-button-popup .vjs-menu .vjs-menu-item,
            .video-js .vjs-menu-button-popup .vjs-menu .vjs-menu-title {
                background-color: #151b17;
                margin: .3em 0;
                padding: .5em;
                border-radius: .3em
            }

            .video-js .vjs-menu-button-popup .vjs-menu .vjs-menu-item.vjs-selected {
                background-color: #2483d5
            }

            .video-js .vjs-big-play-button:active,
            .video-js .vjs-big-play-button:focus,
            .video-js:hover .vjs-big-play-button {
                background-color: rgba(0, 0, 0, 0)
            }

            .video-js .vjs-loading-spinner {
                border-color: #b99beb
            }

            .video-js .vjs-control-bar2 {
                background-color: #000
            }

            .video-js .vjs-control-bar {
                background-color: rgba(0, 0, 0, .4) !important;
                color: #fff;
                font-size: 14px
            }

            .video-js .vjs-play-progress,
            .video-js .vjs-volume-level {
                background-color: #b99beb
            }

            .video-js .vjs-load-progress {
                background: rgba(255, 255, 255, .8)
            }

            .video-js .vjs-big-play-button:hover {
                color: #b99beb
            }

            .video-js .vjs-control:focus:before,
            .video-js .vjs-control:hover:before {
                color: #b99beb;
                text-shadow: none
            }

            /* GC CSS UPDATE */
            .video-js button {
                /*GC*/
                outline: none;
            }

            .video-js .vjs-live-display::before {
                content: "•";
                font-size: 180%;
                padding-right: 5px;
                vertical-align: sub;
                color: rgb(204, 204, 204);
            }

            .video-js.vjs-paused .vjs-live-display:before,
            .video-js.vjs-waiting .vjs-live-display:before,
            .video-js.vjs-seeking .vjs-live-display:before {
                color: #ccc;
            }

            .video-js.vjs-playing .vjs-live-display:before {
                color: red;
            }

            /* SETTING BECAUSE OF BOOTSTRAP */
            .vjs-modal-dialog-contentbutton,
            input,
            optgroup,
            select,
            textarea {
                color: black !important;
            }
        </style>

</body>

</html>
