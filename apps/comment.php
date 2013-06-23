<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/pics.php');
include_once('../class/users.php');
include_once('../class/comments.php');

$c = new Comments ();

$do = isset($_POST['do']) ? $_POST['do'] : 'not';
$pic = isset($_POST['pic']) ? $_POST['pic'] : 'not';
$content = isset($_POST['content']) ? $_POST['content'] : 'not';

if ($do != 'not') {
	$ccomment = $c->comment($pic, $content);
	if ($ccomment) {
		echo '1:' . $pic . ':' . $content;
	}else{
		echo '0:' . $pic . ':' . $c->error();
	}
}
?>