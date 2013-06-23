<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/pics.php');
include_once('../class/users.php');
include_once('../class/comments.php');

$key = isset($_GET['key']) ? $_GET['key'] : false;
$value = isset($_GET['value']) ? $_GET['value'] : false;
$step = isset($_GET['step']) ? $_GET['step'] : '1';
$pics = new Pics ();

$json = $pics->listar($key, $value, $step);
$json = json_encode($json);

header('Content-type: text/javascript');

echo $json;

?>