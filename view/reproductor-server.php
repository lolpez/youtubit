<script src="../resources/jquery/jquery.min.js"></script>

<div id="player"></div>


<script>
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    var player;
    var pk_video_actual = parseInt('<?php echo $video_actual->pkvideo ?>');
    var id_video_actual = '<?php echo $video_actual->id ?>';
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            height: '500',
            width: '500',
            videoId: id_video_actual,
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
		
    }
    function onPlayerReady(event) {
		event.target.setPlaybackQuality('small');
        event.target.playVideo();
		
    }

    var done = false;

    function onPlayerStateChange(event) {
        if(event.data === 0) {
            $.ajax({
                method: "POST",
                url: '../index.php?web_service=siguiente',
                data: {pkvideo : pk_video_actual},
                async: false
            }).done(function (response) {
                response = JSON.parse(response);
                console.log(response);
                if (response){
                    pk_video_actual = parseInt(response.pkvideo);
                    id_video_actual = response.id;
                    player.loadVideoById(id_video_actual);
                }
            })
        }
    }

    function stopVideo() {
        player.stopVideo();
    }



    setInterval(function () {
        $.ajax({
            method: "POST",
            url: '../index.php?web_service=actualizar',
            data: '',
            async: false
        }).done(function (response) {
            response = JSON.parse(response);
            console.log(response);
            if (response){
                if (response.pkvideo != pk_video_actual){
                    pk_video_actual = parseInt(response.pkvideo);
                    id_video_actual = response.id;
                    player.loadVideoById(id_video_actual);
                }
            }
        })
    }, 3000);

</script>
