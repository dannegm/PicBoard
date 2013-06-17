<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/pics.php');

$pass = isset($_GET['pass']) ? $_GET['pass'] : 'not';

if ($pass == 'A3dS4fD5g') {
	$pics = new Pics ();
	$update = $pics->update_master_values();
	if ($update) {
		echo "Se han actaulizado todas las imágenes";
	} else {
		echo "Las siguientes imagenes no se han actaulizado\n\n";
		$error_pics = explode(';', $update);
		for ($i; $i > count($error_pics); $i++){
			echo $error_pics[$i] . "\n";
		}
	}
}else{
	echo "No tienes permisos para hacer esto";
}

?>