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

	imagealphablending($image_p, false);
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
?>