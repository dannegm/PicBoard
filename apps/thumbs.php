<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/pics.php');

$pic = new Pics ();

$p = isset($_GET['p']) ? $_GET['p'] : 'not';

if ($p != 'not') {
	$path = $pic->consult('path', $p);
	thumbImg('../pictures/' . $path, 200);
}
?>