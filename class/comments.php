<?php
class Users
{
	private $_uid;

	private $_db_server = db_server;
	private $_db_user = db_user;
	private $_db_pass = db_password;
	private $_db_bdata = db_bdata;
	private $_tb_comments = tb_comments;

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

	public function getComments ($who) {
		$conexion = $this->_mysqli;
		$query = "SELECT * FROM `{$this->_tb_comments}` WHERE `picture` = '{$who}'";

		if ($get_data = $conexion->query($query)){
			while($result = $get_data->fetch_assoc()){
				if ( $result['status'] == '1') {

					$user = new Users ();
					$author = $user->getUser($result['author']);

					$res = Array(
						'index' => $result['id'],
						'uid' => $result['uid'],
						'content' => $result['content'],
						'date' => $result['date'],
						'author' => $author
					);
					return $res;
				}
			}
		}
	}

	private function comment ($who, $content) {
		$conexion = $this->_mysqli;

		$uid = genKey("uid");
			$this->_uid = $uid;

		session_start();
		$author = $_SESSION['fbId'];

		date_default_timezone_set("America/Mexico_City");
			$date = date("w j-m-Y g:i:s:a");

		$status = '1';

		$query = "INSERT INTO `{$this->_tb_comments}` (`uid`, `picture`, `author`, `content`, `date`, `status`)"
			. "VALUES (?, ?, ?, ?, ?, ?)"
		;
		$ins = $conexion->prepare($query);
		$ins->bind_param( 'ssssss', $uid, $who, $author, $content, $date, $status);
		$insert = $ins->execute();

		if ( !$insert ) {
			$this->_error = "No se pudo registrar usuario";
			return false;
		}else{
			return true;
		}
	}
}