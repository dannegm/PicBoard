<?php
	include_once('config.php');
	include_once('php/facebook.php');
	include_once('php/functions.php');
	include_once('class/pics.php');
	include_once('class/users.php');
	include_once('class/comments.php');

	error_reporting(0);

	$config = array(
		'appId' => '152670424917089',
		'secret' => '16551a9e1ea9ba03d341026f0807b819'
	);
	$facebook = new Facebook($config);
	$fbId = $facebook->getUser();
	$fbToken = $facebook->getAccessToken();

	$user = new Users ();
	$user->login( $fbId, $fbToken );

	$cForm = '';
	if ($fbId) {
		$cForm = "<form id=\"cForm\"><img id=\"muPictureForm\" src=\"http://graph.facebook.com/{$fbId}/picture?type=large\" /><p id=\"cContent\" contenteditable>Comenta ésta imagen...</p></form>";
	}

	$domain = "http://dannegm.pro/picboard/";
	$p = isset($_GET['p']) ? $_GET['p'] : 'DRUStktZ';

	$p = explode('/', $_SERVER['PHP_SELF']);
		$p = end($p);

	$pic = file_get_contents($domain . $p . '?info');
		$pic = json_decode($pic);

	$picode = $domain . $p . '?resize';
	if ( $pic->mimetype == 'image/gif') {
		$picode = $domain . $p;
	}

	$authorID = $pic->author->fbId;
	$authorToken = $user->getAccessToken($authorID);
?>
<!doctype html>
<!-- [ Power By Dannegm (c) 2013 - http://dannegm.pro ] -->
<html lang="en">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# picboard: http://ogp.me/ns/fb/picboard#">
        
	<meta property="fb:app_id" content="152670424917089" />

	<meta name="twitter:card" content="photo" />
	<meta name="twitter:site" content="dannegmweb">
	<meta name="twitter:image" content="http://dannegm.pro/picboard/<?php echo $p; ?>?resize" />
	<meta name="twitter:image:width" content="<?php echo $pic->width; ?>" />
	<meta name="twitter:image:height" content="<?php echo $pic->height; ?>" />

	<meta property="og:type" content="picboard:picture" />
	<meta property="og:site_name" content="Dannegm Picboard" />

	<meta property="og:image" content="http://dannegm.pro/picboard/<?php echo $p; ?>?thumb" />
	<meta property="og:url" content="http://dannegm.pro/picboard/viewer/<?php echo $p; ?>" />

	<meta property="og:title" content="" />
	<meta property="og:description" content="Imagen de <?php echo $pic->author->name; ?> en Picboard" />

	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Dannegm Picboard</title>

	<link rel="stylesheet/less" href="<?php echo $domain; ?>less/default.less" />

	<script src="<?php echo $domain; ?>js/jquery.min.js"></script>
	<script src="<?php echo $domain; ?>js/less.min.js"></script>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-30390599-3', 'dannegm.pro');
		ga('send', 'pageview');

		var fbId = '<?php echo $fbId; ?>',
			cdomain = 'http://dannegm.pro/picboard/';
		function init () {
			$('#cContent').focus(function(){
				$(this).html('');
			});
			$('#cContent').blur(function(){
				if ( $(this).html() == '' ){
					$(this).html('Comenta ésta imagen...');
				}
			});

			$('#goToPicture').click(function(e){
				e.preventDefault();

				var mLeft = ($(window).width() /2) - 75;
				$('#pop p').css('margin-left', mLeft + 'px');

				$('body').css('overflow', 'hidden');
				$('#pop').fadeIn();

				setTimeout(function(){
					$('#pop p').fadeOut();
				}, 5 * 1000);
			});
			$('#pop').click(function(){
				$('body').css('overflow', 'auto');
				$('#pop').fadeOut();
				$('#pop p').show();
			});

			$('#cContent').keypress(function(e){
				var key = e.keyCode;
				if(!e.shiftKey){
					if (key == '13'){
						if ( $(this).text() != '' ){
							e.preventDefault();
							$.post(cdomain + 'apps/comment.php', {
								'do': 'it',
								'pic': '<?php echo $p; ?>',
								'user': fbId,
								'content': $('#cContent').text()
							}, function(re){
								var res = re.split(':');
								if (res[0] != '0'){

<?php /*
								var
									fbToken = '<?php echo $authorToken; ?>';
									url = 'https://graph.facebook.com/<?php echo $pic->author->fbId; ?>/notifications',
									params = {
										'access_token': fbToken,
										'href': 'viewer.php/<?php echo $p; ?>',
										'template': '@[' + fbId + '] ha comentado tu foto en Picboard'
									};
								$.post(url, params, console.log);

*/ ?>

									var tmp = '<article><img src="http://graph.facebook.com/' + fbId + '/picture" /><p><strong>' + res[2] + '</strong><span>' + res[3] + '</span></p></article>';
									$('#comments').prepend(tmp);
									$('#cContent').html('');
								}else{
									alert(re);
								}
								console.log(re);
							});
						}
					}
				}
			});
		}
		$(document).ready(init);
	</script>
</head>
<body class="viewer">
	<header>
		<div class="center">
			<h1><a href="http://dannegm.com">Dannegm</a></h1>
			<h2>PicBoard</h2>
			<ul>
				<li><a href="<?php echo $domain; ?>">Inicio</a></li>
				<li id="goToAbout"><a href="#">Acerca de</a></li>
			</ul>
		</div>
	</header>
	<section id="container">
		<article id="picture" style="display: block;">
			<figure>
				<img id="pPicture" src="<?php echo $picode; ?>" />
			</figure>
			<div>
				<div class="miniProfile">
					<figure>
						<img id="muPicture" src="http://graph.facebook.com/<?php echo $pic->author->fbId; ?>/picture?type=large" />
					</figure>
					<div>
						<strong id="muName"><?php echo $pic->author->name; ?></strong>
						<span id="pDate"><?php echo format_date($pic->date); ?></span>
					</div>
				</div>

				<div class="tools">
					<input id="pLink" type="text" placeholder="Url de la imágen" value="<?php echo $domain . $p; ?>" />
					<a id="goToPicture" class="btn" href="<?php echo $domain . $p; ?>" target="_blank">Ver en tamaño completo</a>
					<a id="download" class="btn clear" href="<?php echo $domain . $p . '?download'; ?>">Descargar</a>
				</div>

				<?php echo $cForm; ?>

				<section id="comments">

<?php
	$comments = array_reverse($pic->comments);
	foreach ($comments as $comment) {
		$FormatDate = format_date_hr($comment->date);

		$content = $comment->content;
			$content = str_replace('[s]', '<br />', $content);
		$tmp = "<article><img src=\"http://graph.facebook.com/{$comment->author->fbId}/picture\" /><p><strong>{$comment->author->name} <time>{$FormatDate}</time></strong><span>{$content}</span></p></article>";
		echo $tmp;
	}
?>

				</section>
			</div>
		</article>
		<p>
			<span>Proyecto desarollodado por <a href="http://github.com/dannegm">@dannegm</a>, no se te olvide seguir el proyecto en <a href="https://github.com/dannegm/PicBoard/" target="_blank">github</a>.</span>
		</p>
	</section>

	<section id="pop">
		<p>Click para cerrar</p>
		<img src="<?php echo $domain . $p; ?>" />
	</section>
</body>
</html>