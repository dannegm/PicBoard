<!doctype html>
<!-- [ Power By Dannegm (c) 2012 - http://dannegm.com ] -->
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Dannegm Picboard</title>

	<link rel="stylesheet/less" href="less/default.less" />

	<script src="js/jquery.min.js"></script>
	<script src="js/less.min.js"></script>
	<script src="js/script.js"></script>
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
		<h1><a href="http://dannegm.pro">Dannegm</a></h1>
		<h2>PicBoard</h2>
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
	</section>

	<section id="container">
		<article id="picture">
			<figure>
				<img id="imgPicture" src="http://dannegm.pro/picboard/bD32WvQs" />
			</figure>
			<div>
				<span id="datePicture">Jueves 06 de mayo del 2013</span>
				<input id="linkPicture" type="text" placeholder="Url de la imágen" />
				<a id="goToPicture" class="btn" href="#">Ver en tamaño completo</a>
				<a id="goToPictures" class="btn clear" href="#">Regresar</a>

				<div id="fbCommets"></div>
			</div>
		</article>

		<ul id="pictures">
		</ul>
	</section>
</body>
</html>