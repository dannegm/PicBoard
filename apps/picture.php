<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/pics.php');

$pic = new Pics ();

$p = isset($_GET['p']) ? $_GET['p'] : 'not';

if ($p != 'not') {
	$picture = $pic->printImg($p);

	header('Content-type: image/' . $picture['ext']);
	echo $picture['code'];
}
?>