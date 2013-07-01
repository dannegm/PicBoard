<?php
	$domain = "http://dannegm.pro/picboard/";
	$p = isset($_GET['p']) ? $_GET['p'] : 'DRUStktZ';

	$p = explode('/', $_SERVER['PHP_SELF']);
		$p = end($p);

	$pic = file_get_contents($domain . $p . '?info');
		$pic = json_decode($pic);

	function format_date ($date) {
		$date = explode(' ', $date);
		$diasem = (int) $date[0];

		$fecha = explode('-', $date[1]);
			$dia = $fecha[0];
			$mes = (int) $fecha[1];
			$year = $fecha[2];

		$tMes = 'nohay enero febrero marzo abril mayo junio julio agosto septiembre octubre noviembre diciembre';
		$tDia = 'domingo lunes martes miércoles jueves viernes sábado';
			$tMes = explode(' ', $tMes);
			$tDia = explode(' ', $tDia);

			$diasem = $tDia[$diasem];
			$mes = $tMes[$mes];

		$formatDate = 'El ' . $diasem . ' ' . $dia . ' de ' . $mes . ' del ' . $year;
		return $formatDate;
	}
	function format_date_hr ($date) {
		$date = explode(' ', $date);

		$fecha = explode('-', $date[1]);
			$dia = $fecha[0];
			$mes = (int) $fecha[1];
			$year = $fecha[2];

		$tMes = 'nohay ene feb mar abr may jun jul ago sep oct nov dic';
			$tMes = explode(' ', $tMes);
			$mes = $tMes[$mes];

		$hora = explode(':', $date[2]);
			$Hr = $hora[0];
			$Mi = $hora[1];
			$Me = $hora[3];

		$formatDate = $dia . ' de ' . $mes . ' del ' . $year . ' a las ' . $Hr . ':' . $Mi . $Me;
		return $formatDate;
	}
?>
<!doctype html>
<!-- [ Power By Dannegm (c) 2013 - http://dannegm.pro ] -->
<html lang="en">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# picboard: http://ogp.me/ns/fb/picboard#">
        
	<meta property="fb:app_id" content="152670424917089" />
	<meta property="og:title" content="Sample Picture" />
	<meta property="og:image" content="http://dannegm.pro/picboard/<?php echo $p; ?>?thumb" />
	<meta property="og:url" content="http://dannegm.pro/picboard/viewer/<?php echo $p; ?>" />
	<meta property="og:type" content="picboard:picture" />

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
<body class="viewer">
	<header>
		<div class="center">
			<h1><a href="http://dannegm.com">Dannegm</a></h1>
			<h2>PicBoard</h2>
			<ul>
				<li><a href="<?php echo $domain; ?>">Inicio</a></li>
				<li id="goToAbout">Acerca de</li>
			</ul>
		</div>
	</header>
	<section id="container">
		<article id="picture" style="display: block;">
			<figure>
				<img id="pPicture" src="<?php echo $domain . $p; ?>" />
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
					<a class="btn" href="<?php echo $domain . $p; ?>" target="_blank">Ver en tamaño completo</a>
				</div>
				<section id="comments">

<?php
	foreach ($pic->comments as $comment) {
		$FormatDate = format_date_hr($comment->date);
		$tmp = "<article><img src=\"http://graph.facebook.com/{$comment->author->fbId}/picture\" /><p><strong>{$comment->author->name} <time>{$FormatDate}</time></strong><span>{$comment->content}</span></p></article>";
		echo $tmp;
	}
?>

				</section>
			</div>
		</article>
	</section>
</body>
</html>