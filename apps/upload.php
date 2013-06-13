<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/pics.php');

$pic = new Pics ();

$suibr = $pic->upload($_FILES['images']);

if ($suibr){
	echo '1:Se guardó la imagen:' . $pic->uid();
}else{
	$error = explode(':', $pic->error());
	if ($error[0] == 'img_exist'){
		$uri = $pic->find_pic($error[1]);
		echo "0:1:" . $uri;
	}else{
		echo '0:' . $pic->error();
	}
}
?>