<?php
	$domain = "http://dannegm.pro/picboard/";
?>
<!doctype html>
<!-- [ Power By Dannegm (c) 2013 - http://dannegm.pro ] -->
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Dannegm Picboard</title>

	<link rel="stylesheet/less" href="<?php echo $domain; ?>less/default.less" />

	<script src="<?php echo $domain; ?>js/jquery.min.js"></script>
	<script src="<?php echo $domain; ?>js/less.min.js"></script>
	<script src="<?php echo $domain; ?>js/script.js"></script>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-30390599-3', 'dannegm.pro');
		ga('send', 'pageview');
	</script>
</head>
<body class="noSidebar">

<div id="fb-root"></div>
<script>
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/es_MX/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
</script>

	<header>
		<h1><a href="http://dannegm.com">Dannegm</a></h1>
		<h2>PicBoard</h2>
		<ul>
			<li id="goToHome" class="active">Inicio</li>
			<li id="goToProfile">Mis imágenes</li>
			<li id="goToAbout">Acerca de</li>
		</ul>
	</header>

	<section id="noLogin">
		<div>
			<h3>Logueate con facebook</h3>
			<p>Para poder subir tus imágenes es necesario iniciar sesión con facebook</p>
			<button id="intoFace">Iniciar con facebook</button>
		</div>
	</section>

	<section id="sidebar">
		<article id="profile">
			<figure>
				<img id="uPicture" src="#" />
			</figure>
			<div>
				<strong id="uName"></strong>
				<span>Ha subido <em id="uCount"></em> imágenes</span>
				<button class="btn viewProfile" id="uGoToProfile" rel="user">Ver imágenes</button>
			</div>
		</article>
		<form>
			<input type="file" id="uploadImages" name="images" />
			<div id="dropbox">
				<div id="before">
					<span>Arrastra tu imagen <em>aquí</em></span>
					<strong>ó</strong>
					<button id="btn_upload" class="btn">Seleccionala</button>
				</div>
				<div id="uploading">
					<span id="progressTxt"></span>
					<canvas id="progress" width="120" height="120"></canvas>
				</div>
			</div>
		</form>
		<div>
			<span id="elink">
	 			<input id="newLink" type="text" placeholder="Url de la imágen" />
			</span>
			<span id="exist">Ya existía en nuestra base de datos</span>
			
			<figure>
				<img id="preview" src="#" />
			</figure>
		</div>

		<p>
			<span>Proyecto desarollodado por <a href="http://github.com/dannegm">@dannegm</a>, no se te olvide seguir el proyecto en github.</span>
			<iframe class="github-btn" src="http://ghbtns.com/github-btn.html?user=dannegm&amp;repo=PicBoard&amp;type=fork&amp;count=true" allowtransparency="true" frameborder="0" scrolling="0" width="260px" height="30px"></iframe>
		</p>
	</section>

	<section id="container">
		<article id="picture">
			<figure>
				<img id="pPicture" src="#" />
			</figure>
			<div>
				<div class="miniProfile">
					<figure>
						<img id="muPicture" src="#" />
					</figure>
					<div>
						<strong id="muName"></strong>
						<span id="pDate"></span>
					</div>
				</div>

				<div class="tools">
					<input id="pLink" type="text" placeholder="Url de la imágen" />
					<a id="goToPicture" class="btn" href="#">Ver en tamaño completo</a>
					<a id="goToPictures" class="btn clear" href="#">Regresar</a>
				</div>

				<form id="cForm">
					<img id="muPictureForm" src="#" />
					<p id="cContent" contenteditable>Comenta ésta imagen...</p>
				</form>
				<section id="comments">
				</section>
			</div>
		</article>

		<ul id="pictures">
		</ul>
	</section>
</body>
</html>