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
		$offset = $step * 30;

		switch ($key) {
			case 'author': $query = "SELECT * FROM `{$this->_tb_pics}` WHERE `author` = '{$value}' AND `status` = '1' ORDER BY `id` DESC LIMIT {$offset},30"; break;
			case 'picture': $query = "SELECT * FROM `{$this->_tb_pics}` WHERE `uid` = '{$value}'"; break;
			case 'width': $query = "SELECT * FROM `{$this->_tb_pics}` WHERE `width` > '{$value}' AND `status` = '1' ORDER BY `id` DESC LIMIT {$offset},30"; break;
			default: $query = "SELECT * FROM `{$this->_tb_pics}` WHERE `status` = '1' ORDER BY `id` DESC LIMIT {$offset},30"; break;
		}

		$conexion = $this->_mysqli;
		if ($get_data = $conexion->query($query)){
			$res = Array();
			while($result = $get_data->fetch_assoc()){
				$Comments = new Comments ();
				
				if ($key == 'picture') {
					$user = new Users ();
					$author = $user->getUser($result['author']);
					$Comments = $Comments->getComments($result['uid']);
				}else{
					$author = $result['author'];
					$Comments = $Comments->count($result['uid']);
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
					'height' => $result['height'],
					'comments' => $Comments
				//	'prints' => $result['prints']
				);
			}
			return $res;
		}
	}

	public function getInfo ($value) {
		$conexion = $this->_mysqli;
		$query = "SELECT * FROM `{$this->_tb_pics}` WHERE `uid` = '{$value}'";
		$conexion = $this->_mysqli;
		if ($get_data = $conexion->query($query)){
			while($result = $get_data->fetch_assoc()){
				$Comments = new Comments ();
			
				$user = new Users ();
				$author = $user->getUser($result['author']);
				$Comments = $Comments->getComments($result['uid']);

				$res = Array(
					'index' => $result['id'],
					'id' => $result['uid'],
					'author' => $author,
					'date' => $result['date'],
					'md5' => $result['md5'],
					'mimetype' => $result['mimetype'],
					'width' => $result['width'],
					'height' => $result['height'],
					'comments' => $Comments
				);
				return $res;
			}
		}
	}

	public function getThumb ($value) {
		$path = $this->consult('path', $value);
		thumbImg('../pictures/' . $path, 200);
	}

	public function update_master_values () {
		$conexion = $this->_mysqli;
		$query = "SELECT * FROM `{$this->_tb_pics}` ORDER BY `id` DESC";

		$conexion = $this->_mysqli;
		if ($get_data = $conexion->query($query)){
			$res = Array();
			while($result = $get_data->fetch_assoc()){
				$uid = $result['uid'];
				$path = $result['path'];

				$filename = "../pictures/" . $path;
				$infPic = getimagesize( $filename );
				list($width, $height) = $infPic;
				$mime = $infPic['mime'];

				$up_query = "UPDATE `{$this->_tb_pics}` SET `width` = ?, `height` = ?, `mimetype` = ? WHERE `uid` = '{$uid}'";
				$up = $conexion->prepare($up_query);

				$up->bind_param ( 'sss', $width, $height, $mime );
				$upd = $up->execute();


				if ( !$upd ) {
					$this->_error = "{$this->_error};{$uid}";
				}
			}
			if ($this->_error == "No hay error") {
				return true;
			}else{
				return false;
			}
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

		$uid = genKey();
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

		$status = '1';

		if ($content != '') {
			$find = $this->_exist($md5);
			if (!$find){
				$query = "INSERT INTO `{$this->_tb_pics}` (`uid`, `path`, `date`, `content`, `md5`, `author`, `width`, `height`, `mimetype`, `status`)"
					. "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
				;
				$ins = $conexion->prepare($query);
				$ins->bind_param( 'ssssssssss', $uid, $uri, $date, $content, $md5, $author, $width, $height, $mime, $status);
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
		$mime = $this->consult('mimetype', $uid);

		$picture = $this->consult('content', $uid);
			$picture = base64_decode($picture);

		return Array(
			'ext' => $ext,
			'mimetype' => $mime,
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

	private function _update ($who, $what, $newVal) {
		$conexion = $this->_mysqli;
		$up_query = "UPDATE `{$this->_tb_pics}` SET `{$what}` = ? WHERE `uid` = '{$who}'";
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

	public function del ($who) {
		session_start();
		$author = $_SESSION['fbId'];
		$picAuthor = $this->consult('author', $who);

		if ($picAuthor == $author || $picAuthor == '1284130965'){
			$res = $this->_update($who, 'status', '0');
			if ($res) {
				return true;
			}else{
				$this->_error = "No se pudo eliminar ésta imagen";
				return false;
			}
		}else{
			$this->_error = "No tienes permisos para hacer ésto";
			return false;
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

	public function exist ($who) {
		$conexion = $this->_mysqli;
		$sql = "SELECT * FROM `{$this->_tb_pics}` WHERE `uid` = '{$who}'";
		$conexion->query($sql);
		$n = $conexion->affected_rows;
		if ($n > 0) {
			return true;
		}else{
			return false;
		}
	}

	public function count ($who) {
		$conexion = $this->_mysqli;
		$sql = "SELECT * FROM `{$this->_tb_pics}` WHERE `author` = '{$who}' AND `status` = '1'";
		$conexion->query($sql);
		return $conexion->affected_rows;
	}
}