<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/pics.php');
include_once('../class/users.php');
include_once('../class/comments.php');

$c = new Comments ();

$do = isset($_POST['do']) ? $_POST['do'] : 'not';
$pic = isset($_POST['pic']) ? $_POST['pic'] : 'not';
$user = isset($_POST['user']) ? $_POST['user'] : 'not';
$content = isset($_POST['content']) ? $_POST['content'] : 'not';

if ($do != 'not') {
	$u = new Users ();
	$user = $u->getUser($user);
	$name = $user['name'];

	$content = str_replace('<3', '♥', $content);

	$content = utf8_decode($content);
	$content = htmlentities($content);
	$ccomment = $c->comment($pic, $content);

	if ($ccomment) {
		echo '1:' . $pic . ':' . $name . ':' . $content;
	}else{
		echo '0:' . $pic . ':' . $c->error();
	}
}
?>