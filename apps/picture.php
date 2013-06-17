<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/pics.php');

$pic = new Pics ();

$p = isset($_GET['p']) ? $_GET['p'] : 'not';

if ($p != 'not') {
	if (isset($_GET['thumb'])) {
		$path = $pic->consult('path', $p);
		thumbImg('../pictures/' . $path, 200);
	}else{
		$picture = $pic->printImg($p);

		header('Content-type: ' . $picture['mimetype']);
		echo $picture['code'];
	}
}
?>