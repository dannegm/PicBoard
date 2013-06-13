<?php
include_once('../config.php');
include_once('../php/functions.php');
include_once('../class/users.php');
include_once('../class/pics.php');

$user = new Users ();
$fbId = isset($_POST['fbId']) ? $_POST['fbId'] : 'not';

$result = $user->login( $fbId );

//header('Content-type: text/javascript');
echo json_encode($result);

?>