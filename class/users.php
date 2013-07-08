<?php
class Users
{
	private $_uid;

	private $_db_server = db_server;
	private $_db_user = db_user;
	private $_db_pass = db_password;
	private $_db_bdata = db_bdata;
	private $_tb_users = tb_users;

	private $_mysqli;
	private $_error = "No hay error";

	public function __construct () {
		$mysqli = new mysqli($this->_db_server, $this->_db_user, $this->_db_pass, $this->_db_bdata);
		if ( mysqli_connect_errno ()) {
			$this->_error = "No se pudo conectar con la base de datos";
			return false;
		}else{
			$this->_mysqli = $mysqli;
			return true;
		}
	}

	public function uid () {
		return $this->_uid;
	}

	public function error () {
		return $this->_error;
	}

	public function getUser ($who) {
		$conexion = $this->_mysqli;
		$query = "SELECT * FROM `{$this->_tb_users}` WHERE `fbId` = '{$who}'";

		if ($get_data = $conexion->query($query)){
			while($result = $get_data->fetch_assoc()){

				$pic = new Pics ();
				$countPictures = $pic->count($who);
				$res = Array(
					'index' => $result['id'],
					'fbId' => $result['fbid'],
					'username' => $result['username'],
					'name' => $result['name'],
					'date' => $result['date'],
					'count' => $countPictures,
					'role' => $result['role']
				);
				return $res;
			}
		}
	}

	private function _consult ($what, $who) {
		$query = "SELECT `{$what}` FROM `{$this->_tb_users}` WHERE `fbid` = '{$who}'";
		$conexion = $this->_mysqli;
		if ($get_data = $conexion->query($query)){
			while($result = $get_data->fetch_assoc()){
				return $result[$what];
			}
		}
	}

	public function getAccessToken ($who) {
		return $this->_consult('fbToken', $who);
	}

	private function _update ($who, $what, $newVal) {
		$conexion = $this->_mysqli;
		$up_query = "UPDATE `{$this->_tb_users}` SET `{$what}` = ? WHERE `fbid` = '{$who}'";
		$up = $conexion->prepare($up_query);

		$up->bind_param ( 's', $newVal );
		$upd = $up->execute();

		if ( !$upd ) {
			$this->_error = "No se pudo actualizar éste dato";
			return false;
		}else{
			return true;
		}
	}

	public function login ($who) {
	/*	$access_token = 'https://graph.facebook.com/oauth/access_token?' .            
						'client_id=152670424917089&' .
						'client_secret=16551a9e1ea9ba03d341026f0807b819&' .
						'grant_type=fb_exchange_token&' .
						'fb_exchange_token=' . $token;

		$access_token = file_get_contents($access_token);
			$access_token = explode('&', $access_token);
			$access_token = $access_token[0];
			$access_token = explode('=', $access_token);
			$access_token = $access_token[1]; */

		$isRegister = $this->_exist($who);
		if (!$isRegister) {
			$doRegister = $this->register($who);
		}

		session_start();
		$_SESSION['fbId'] = $who;
		$user = $this->getUser($who);
		//$this->_update($who, 'fbToken', $access_token);
		return $user;
	}

	private function register ($who) {
		$conexion = $this->_mysqli;

		$user = file_get_contents('http://graph.facebook.com/' . $who);
			$user = json_decode($user);

		$username = $user->username;
		$name = $user->name;

		$role = "user";

		date_default_timezone_set("America/Mexico_City");
			$date = date("w j-m-Y g:i:s:a");

		$query = "INSERT INTO `{$this->_tb_users}` (`fbId`, `username`, `name`, `date`, `role`)"
			. "VALUES (?, ?, ?, ?, ?)"
		;
		$ins = $conexion->prepare($query);
		$ins->bind_param( 'sssss', $who, $username, $name, $date, $role);
		$insert = $ins->execute();

		if ( !$insert ) {
			$this->_error = "No se pudo registrar usuario";
			return false;
		}else{
			return true;
		}
	}

	private function _exist ($who) {
		$conexion = $this->_mysqli;
		$sql = "SELECT * FROM `{$this->_tb_users}` WHERE `fbId` = '{$who}'";
		$conexion->query($sql);
		$n = $conexion->affected_rows;
		$n++;
		if ($n > 1) {
			return true;
		}else{
			return false;
		}
	}
}