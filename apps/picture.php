<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/pics.php');
include_once('../class/comments.php');
include_once('../class/users.php');

$pic = new Pics ();

$p = isset($_GET['p']) ? $_GET['p'] : 'not';

if ($pic->exist($p)) {
	if (isset($_GET['thumb'])) {

		$pic->getThumb($p);
		
	}elseif (isset($_GET['resize'])) {

		$size = $_GET['resize'];
		if ($size == ('' || null || 0)) $size = 450;
		$pic->resize($p, $size);

	}elseif (isset($_GET['info'])) {

		$json = $pic->getInfo($p);
		$json = json_encode($json);

		header('Content-type: text/javascript');
		echo $json;

	}elseif (isset($_GET['download'])) {

		$picture = $pic->printImg($p);

		$filename = $p . '.' . $picture['ext'];

		header('Cache-Control: public');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename=' . $filename);
		header('Content-type: ' . $picture['mimetype']);
		header('Content-Transfer-Encoding: binary');

		echo $picture['code'];

	}else{

		$picture = $pic->printImg($p);

		header('Content-type: ' . $picture['mimetype']);
		echo $picture['code'];

	}
}else{
	header("HTTP/1.0 404 Not Found");
}
?>