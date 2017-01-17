<div class="box-row">
    <div class="box-cell">
        <div class="box-inner padding">
            <div class="row row-sm">
                <div class="col-sm-6">
                    <div class="panel panel-card">
                        <div class="card-heading">
                            <h2 id="video-titulo"><?php echo $video_actual->titulo ?></h2>
                            <small>Reproduciendo ahora en el servidor</small>
                        </div>
                        <div class="r-t pos-rlt">
                            <div class="p-lg bg-white-overlay text-center r-t" style="height: 500px">
                                <div id="player"></div>
                            </div>
                        </div>
                        <div class="list-group no-radius no-border">
                            <a id="video-url" href="<?php echo $video_actual->url ?>" class="list-group-item">
                                URL: <span id="video-url-titulo"><?php echo $video_actual->url ?></span>
                            </a>
                            <span class="list-group-item">
                                ID: <span id="video-id"><?php echo $video_actual->id ?></span>
                            </span>
                        </div>
                        <div class="text-center b-b b-light">
                            <span class="inline m text-color">
                                <span class="h3 block font-bold"><span id="video-pkvideo"><?php echo $video_actual->pkvideo ?></span></span>
                                <em class="text-xs">Nro. en la lista</em>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-heading">
                            <h2>Agregar un video a la lista</h2>
                            <small>Introduzca la url del video que desea de la pagina de YouTube</small>
                        </div>
                        <div class="panel-body" id="form-url">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input id="url" type="text" class="form-control" placeholder="URL">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-8 col-sm-4">
                                        <button onclick="guardar()" class="btn btn-info pull-right waves-effect">Aceptar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-heading">
                            <h2>Playlist</h2>
                            <small>Lista de videos</small>
                        </div>
                        <ul id="lista" class="list-group" style="height:430px; overflow-y:auto">
                            <?php $i=1; foreach ($lista as $r){ ?>
                                <a id="video-<?php echo $r->pkvideo ?>" onclick="reproducir('<?php echo $r->pkvideo ?>')" class="activo list-group-item <?php if ($r->pkvideo == $video_actual->pkvideo) echo "blue"?>">
                                    <span class="pull-left w-40 m-r"><?php echo $i; $i++ ?> </span>
                                    <span class="block font-bold"><?php echo $r->titulo ?></span>
                                    <small class="text-muted"><?php echo $r->id ?></small>
                                </a>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            height: '100%',
            width: '100%',
            videoId: '<?php echo $video_actual->id ?>',
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }
    function onPlayerReady(event) {

    }

    var done = false;
    function onPlayerStateChange(event) {

    }
    function stopVideo() {
        player.stopVideo();
    }

    function guardar(){
		var loading = $('#form-url').waitMe({
			effect: 'pulse',
			text: 'Cargando...',
			bg: 'rgba(255,255,255,0.90)',
			color: '#555'
		});
        $.ajax({
            method: "POST",
            url: 'index.php?web_service=guardar',
            data: {url : $('#url').val()},
            async: true
        }).done(function (response) {
			response = JSON.parse(response);
			console.log(response);
            if (response.exito){
				objeto = response.objeto; 
                $('#lista').append(
                    "<a id='video-"+ objeto.pkvideo  +"' class='list-group-item' onclick='reproducir(" + objeto.pkvideo + ")'>" +
                    "<span class='pull-left w-40 m-r'>" + objeto.pkvideo +  " </span>"+
                    "<span class='block font-bold'>" + objeto.titulo + " </span>"+
                    "<small class='text-muted'>" + objeto.id + " </small>"+
                    "</a>"
                )
				showMessage(response.mensaje,'success','ok');
				$('#url').val('');
				loading.waitMe('hide');
            }else{
				showMessage(response.mensaje,'danger','remove');
				loading.waitMe('hide');
			}
        })
    }

    function reproducir(pkvideo){
        $.ajax({
            method: "POST",
            url: 'index.php?web_service=reproducir',
            data: {pkvideo : pkvideo},
            async: false
        }).done(function (response) {
            response = JSON.parse(response);
            console.log(response);
            if (response){
                $('.activo').removeClass('blue');
                $('#video-'+response.pkvideo).addClass('blue');
                $('#video-pkvideo').html(response.pkvideo)
                $('#video-titulo').html(response.titulo);
                $('#video-id').html(response.id);
                $('#video-url-titulo').html(response.url);
                $('#video-url').attr('href',response.url);
                player.cueVideoById(response.id);
            }
        })
    }

	
    function showMessage(mensaje,tipo,icono){
		$.notify({
			icon: 'glyphicon glyphicon-' + icono,
			message: mensaje
		},{
			element: 'body',
			position: null,
			type: tipo,
			allow_dismiss: true,
			newest_on_top: false,
			showProgressbar: false,
			placement: {
				from: "top",
				align: "center"
			},
			offset: 20,
			spacing: 10,
			z_index: 1031,
			delay: 5000,
			timer: 1000,
			mouse_over: null,
			animate: {
				enter: 'animated fadeInDown',
				exit: 'animated fadeOutUp'
			},
			onShow: null,
			onShown: null,
			onClose: null,
			onClosed: null,
			icon_type: 'class'
		});
	}
</script>