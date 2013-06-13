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

	public function login ($who) {
		$isRegister = $this->_exist($who);
		if ($isRegister) {
			session_start();
			$_SESSION['fbId'] = $who;
			$user = $this->getUser($who);
			return $user;
		} else {
			$doRegister = $this->register($who);
			if ($doRegister) {
				session_start();
				$_SESSION['fbId'] = $who;
				$user = $this->getUser($who);
				return $user;
			}
		}
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