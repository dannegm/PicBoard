<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/users.php');
include_once('../class/pics.php');

$user = new Users ();
$fbId = isset($_POST['fbId']) ? $_POST['fbId'] : 'not';
$fbToken = isset($_POST['fbToken']) ? $_POST['fbToken'] : 'not';

$result = $user->login( $fbId, $fbToken );

//header('Content-type: text/javascript');
echo json_encode($result);

?>