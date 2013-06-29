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

		$path = $pic->consult('path', $p);
		thumbImg('../pictures/' . $path, 200);

	}elseif (isset($_GET['info'])) {

		$json = $pic->listar('picture', $p, false);
		$json = $json[0];
		$json = json_encode($json);

		header('Content-type: text/javascript');
		echo $json;

	}else{

		$picture = $pic->printImg($p);

		header('Content-type: ' . $picture['mimetype']);
		echo $picture['code'];

	}
}else{
	header("HTTP/1.0 404 Not Found");
}
?>