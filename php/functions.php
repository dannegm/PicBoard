<?php
function genKey () {
	$rCh = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	$key = "";
	for ( $i = 0; $i < 8; $i++ ){
		$key .= $rCh{ rand(0,61) };
	}
	return $key;
}
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
function thumbImg ($img, $size = 200) {

	$image_type = explode(".", $img);
	$image_type = end($image_type);

	if ( preg_match('/jp/i', $image_type) ) { $image_type = "jpeg"; }

	header("Content-Type: image/" . $image_type);

	$image_p = imagecreatetruecolor( $size, $size );

	list($width_o, $height_o) = getimagesize( $img );
	
	if ( $width_o > $height_o ) {
		$ratio_o = $width_o / $height_o;

		$height = $size;
		$width = $size * $ratio_o;
	} else {
		$ratio_o = $height_o / $width_o;

		$width = $size;
		$height = $size * $ratio_o;
	}

	switch ( $image_type ){
		case "jpeg": $image = imagecreatefromjpeg( $img ); break;
		case "png": $image = imagecreatefrompng( $img ); break;
		case "gif": $image = imagecreatefromgif( $img ); break;
	}

	imagealphablending($image_p, true);
	imagesavealpha($image_p, true);

	imagecopyresampled( $image_p, $image, 0, 0, 0, 0, $width, $height, $width_o, $height_o );

	switch ( $image_type ){
		case "jpeg": imagejpeg($image_p, null, 100); break;
		case "png":  imagepng($image_p); break;
		case "gif": imagegif($image_p); break;
	}
	
	imagedestroy($image);
	imagedestroy($image_p);
}
function resizeImg ($img, $size = 450) {

	$image_type = explode(".", $img);
	$image_type = end($image_type);

	if ( preg_match('/jp/i', $image_type) ) { $image_type = "jpeg"; }
	
	header("Content-Type: image/" . $image_type);

	list($width_o, $height_o) = getimagesize( $img );
	
	$ratio_o = $height_o / $width_o;

	$width = $size;
	$height = $size * $ratio_o;	

	$image_p = imagecreatetruecolor( $width, $height );

	switch ( $image_type ){
		case "jpeg": $image = imagecreatefromjpeg( $img ); break;
		case "png": $image = imagecreatefrompng( $img ); break;
		case "gif": $image = imagecreatefromgif( $img ); break;
	}

	imagealphablending($image_p, true);
	imagesavealpha($image_p, true);

	imagecopyresampled( $image_p, $image, 0, 0, 0, 0, $width, $height, $width_o, $height_o );

	switch ( $image_type ){
		case "jpeg": imagejpeg($image_p, null, 100); break;
		case "png":  imagepng($image_p); break;
		case "gif": imagegif($image_p); break;
	}
	
	imagedestroy($image);
	imagedestroy($image_p);
}
function getImgKeyColor($img) {
	$type = getimagesize($img);
		$type = $type['mime'];
		$type = explode('/', $type);
		$type= end($type);

	switch ( $type ){
		case "jpeg": $img = imagecreatefromjpeg( $img ); break;
		case "png": $img = imagecreatefrompng( $img ); break;
		case "gif": $img = imagecreatefromgif( $img ); break;
	}

	$x = 50; $y = 50;
	$color = imagecolorat($img, $x, $y);

	$key = imagecolorsforindex($img, $color);
	return $key;
}
function colorPalette($imageFile, $numColors, $granularity = 5) {
	$granularity = max(1, abs((int)$granularity));
	$colors = array();
	$size = @getimagesize($imageFile);

	if( $size === false ){
		return false;
	}
	$img = @imagecreatefromstring(file_get_contents($imageFile));
	if( !$img ){
		return false;
	}

	for($x = 0; $x < $size[0]; $x += $granularity){
		for($y = 0; $y < $size[1]; $y += $granularity){
			$thisColor = imagecolorat($img, $x, $y);
			$rgb = imagecolorsforindex($img, $thisColor);

			$red = round(round(($rgb['red'] / 0x33)) * 0x33);
			$green = round(round(($rgb['green'] / 0x33)) * 0x33);
			$blue = round(round(($rgb['blue'] / 0x33)) * 0x33);
			$thisRGB = sprintf('%02X%02X%02X', $red, $green, $blue);

			if(array_key_exists($thisRGB, $colors)){
				$colors[$thisRGB]++;
			}else{
				$colors[$thisRGB] = 1;
			}
		}
	}
	arsort($colors);
	return array_slice(array_keys($colors), 0, $numColors);
}
?>