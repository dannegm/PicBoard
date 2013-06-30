<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/pics.php');

$pic = new Pics ();
$p = isset($_POST['pic']) ? $_POST['pic'] : 'not';
$res = $pic->del($p);

if ($res) {
	echo '1:Se ha eliminado';
}else{
	echo '0:' . $p . ':' . $pic->error();
}

?>