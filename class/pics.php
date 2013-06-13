<?php
class Pics
{
	private $_uid;
	private $_url;

	private $_db_server = db_server;
	private $_db_user = db_user;
	private $_db_pass = db_password;
	private $_db_bdata = db_bdata;
	private $_tb_pics = tb_pics;

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

	public function url () {
		return $this->_url;
	}

	public function error () {
		return $this->_error;
	}

	public function listar ($key, $value, $step) {
		$conexion = $this->_mysqli;

		$step = $step -1;
		$offset = $step * 20;

		switch ($key) {
			case 'author': $query = "SELECT * FROM `{$this->_tb_pics}` WHERE `author` = '{$value}' ORDER BY `id` DESC LIMIT {$offset},20"; break;
			case 'picture': $query = "SELECT * FROM `{$this->_tb_pics}` WHERE `uid` = '{$value}'"; break;
			default: $query = "SELECT * FROM `{$this->_tb_pics}` ORDER BY `id` DESC LIMIT {$offset},20"; break;
		}

		$conexion = $this->_mysqli;
		if ($get_data = $conexion->query($query)){
			$res = Array();
			while($result = $get_data->fetch_assoc()){

				if ($key == 'picture') {
					$user = new Users ();
					$author = $user->getUser($result['author']);
				}else{
					$author = $result['author'];
				}
				$res[] = Array(
					'index' => $result['id'],
					'id' => $result['uid'],
					'author' => $author,
					'date' => $result['date'],
				//	'path' => $result['path'],
				//	'content' => $result['content'],
					'md5' => $result['md5'],
					'mimetype' => $result['mimetype'],
					'width' => $result['width'],
					'height' => $result['height']
				//	'prints' => $result['prints']
				);
			}
			return $res;
		}
	}

	public function consult ($what, $who) {
		$query = "SELECT `{$what}` FROM `{$this->_tb_pics}` WHERE `uid` = '{$who}'";
		$conexion = $this->_mysqli;
		if ($get_data = $conexion->query($query)){
			while($result = $get_data->fetch_assoc()){
				return $result[$what];
			}
		}
	}

	// Functions

	public function upload ($pic) {
		$conexion = $this->_mysqli;

		$uid = genKey("uid");
			$this->_uid = $uid;

		$ext = explode('.', $pic['name']);
		$ext = '.' . end($ext);
		$uri = $uid . $ext;
		$this->_url = $uri;
		move_uploaded_file( $pic['tmp_name'], '../pictures/' . $uri );

		$filename = "../pictures/" . $uri;
		$fp = fopen($filename, "r");
		$content = fread($fp, filesize($filename));
			$content = base64_encode($content);
		$md5 = md5($content);

		fclose($fp);

		$infPic = getimagesize( $filename );
		list($width, $height) = $infPic;
		$mime = $infPic['mime'];

		date_default_timezone_set("America/Mexico_City");
			$date = date("w j-m-Y g:i:s:a");

		session_start();
		$author = $_SESSION['fbId'];

		if ($content != '') {
			$find = $this->_exist($md5);
			if (!$find){
				$query = "INSERT INTO `{$this->_tb_pics}` (`uid`, `path`, `date`, `content`, `md5`, `author`, `width`, `height`, `mimetype`)"
					. "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
				;
				$ins = $conexion->prepare($query);
				$ins->bind_param( 'sssssssss', $uid, $uri, $date, $content, $md5, $author, $width, $height, $mime);
				$insert = $ins->execute();

				if ( !$insert ) {
					$this->_error = "No se pudo registrar imagen";
					return false;
				}else{
					return true;
				}
			}else{
				unlink($filename);
				$this->_error = "img_exist:" . $md5;
				return false;
			}
		}else{
			$this->_error = 'No se recibió ninguna imagen';
			return false;
		}
	}

	public function printImg ($uid) {
		$ext = $this->consult('path', $uid);
			$ext = explode('.', $ext);
			$ext = end($ext);
			$ext = strtolower($ext);
			if ($ext == 'jpg') { $ext = 'jpeg'; }

		$picture = $this->consult('content', $uid);
			$picture = base64_decode($picture);

		return Array(
			'ext' => $ext,
			'code' => $picture
		);
	}

	public function find_pic ($who) {
		$query = "SELECT * FROM `{$this->_tb_pics}` WHERE `md5` = '{$who}'";
		$conexion = $this->_mysqli;
		if ($get_data = $conexion->query($query)){
			while($result = $get_data->fetch_assoc()){
				return $result['uid'];
			}
		}
	}

	private function _exist ($who) {
		$conexion = $this->_mysqli;
		$sql = "SELECT * FROM `{$this->_tb_pics}` WHERE `md5` = '{$who}'";
		$conexion->query($sql);
		$n = $conexion->affected_rows;
		$n++;
		if ($n > 1) {
			return true;
		}else{
			return false;
		}
	}

	public function count ($who) {
		$conexion = $this->_mysqli;
		$sql = "SELECT * FROM `{$this->_tb_pics}` WHERE `author` = '{$who}'";
		$conexion->query($sql);
		return $conexion->affected_rows;
	}
}