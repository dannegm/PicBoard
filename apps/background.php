<?php

	header("If-Modified-Since: Tue, 02 Jul 2013 05:00:00 GMT");
	header('If-None-Match: "265cc-14bfd-41cba1c0"');

	$pics = file_get_contents('http://dannegm.pro/picboard/json/listar.php');
		$pics = json_decode($pics);

	$marco = imagecreatetruecolor( '2000', '600' );

	$x = 0;
	$y = 0;
	foreach ($pics as $pic) {

		$uid = $pic->id;
		$img = 'http://dannegm.pro/picboard/' . $uid . '?thumb';

		$image_type = $pic->mimetype;
			$image_type = explode('/', $image_type);
			$image_type = end($image_type);

		switch ( $image_type ){
			case "jpeg": $image = imagecreatefromjpeg( $img ); break;
			case "png": $image = imagecreatefrompng( $img ); break;
			case "gif": $image = imagecreatefromgif( $img ); break;
		}

		imagecopy( $marco, $image, $x, $y, 0, 0, 200, 200 );
		$x = $x + 200;
		if ($x == 2000) {
			$x = 0;
			$y = $y + 200;
		}
		imagedestroy($image);
	}

	header("Content-Type: image/jpeg");
	imagejpeg($marco, null, 50);
	imagedestroy($marco);

?>