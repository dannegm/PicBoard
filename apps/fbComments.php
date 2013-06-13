<!-- [ Power By Dannegm (c) 2012 - http://dannegm.com ] -->
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dannegm Picboard - Facebook Comments</title>
	<script src="../js/jquery.min.js"></script>
	<style>
		body, html, .fb-comments {
			margin: 0;
			padding: 0;
			border: 0;
		}
		body {
			overflow: hidden;
		}
	</style>
</head>
<body>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/es_MX/all.js#xfbml=1&appId=152670424917089";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<div class="fb-comments" data-href="http://dannegm.pro/picboard/<?php echo $_GET['uid']; ?>" data-width="450" data-num-posts="10"></div>
	<script>
	setInterval(function(){
		var wheight = $('body').height();
		var thisFrame = window.top.document.getElementById('fbComments');
		thisFrame.height = wheight;
	}, 500);
	</script>
</body>
</html>