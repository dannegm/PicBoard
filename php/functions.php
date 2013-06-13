<?php
function genKey () {
	$rCh = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	$key = "";
	for ( $i = 0; $i < 8; $i++ ){
		$key .= $rCh{ rand(0,61) };
	}
	return $key;
}
function formatDate ($date) {
	$fecha = explode(' ', $date);
	$diasem = (int) $fecha[0];

	$fecha = explode('-', $fecha[1]);
	$dia = $fecha[0];
	$mes = (int) $fecha[1];
	$year = $fecha[2];

	$tMes = 'nohay enero febrero marzo abril mayo junio julio agosto septiembre octubre noviembre diciembre';
	$tDia = 'doming lunes martes miércoles jueves viernes sábado';
	$tMes = explode(' ', $tMes);
	$tDia = explode(' ', $tDia);

	$diasem = $tDia[$diasem];
	$mes = $tMes[$mes];

	return "El {$diasem} {$dia} de {$mes} del {$year}";
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