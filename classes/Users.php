<?php
require_once('../config.php');
Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_users(){
		if(empty($_POST['password']))
			unset($_POST['password']);
		else
		$_POST['password'] = md5($_POST['password']);
		extract($_POST);
		$data = '';
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=" , ";
				$data .= " {$k} = '{$v}' ";
			}
		}
		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users set {$data}");
			if($qry){
				$id=$this->conn->insert_id;
				$this->settings->set_flashdata('success','User Details successfully saved.');
				foreach($_POST as $k => $v){
					if($k != 'id'){
						if(!empty($data)) $data .=" , ";
						if($this->settings->userdata('id') == $id)
						$this->settings->set_userdata($k,$v);
					}
				}
				if(!empty($_FILES['img']['tmp_name'])){
					if(!is_dir(base_app."uploads/avatars"))
						mkdir(base_app."uploads/avatars");
					$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
					$fname = "uploads/avatars/$id.png";
					$accept = array('image/jpeg','image/png');
					if(!in_array($_FILES['img']['type'],$accept)){
						$err = "Image file type is invalid";
					}
					if($_FILES['img']['type'] == 'image/jpeg')
						$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name']);
					elseif($_FILES['img']['type'] == 'image/png')
						$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name']);
					if(!$uploadfile){
						$err = "Image is invalid";
					}
					$temp = imagescale($uploadfile,200,200);
					if(is_file(base_app.$fname))
					unlink(base_app.$fname);
					$upload =imagepng($temp,base_app.$fname);
					if($upload){
						$this->conn->query("UPDATE `users` set `avatar` = CONCAT('{$fname}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}'");
						if($this->settings->userdata('id') == $id)
						$this->settings->set_userdata('avatar',$fname."?v=".time());
					}

					imagedestroy($temp);
				}
				return 1;
			}else{
				return 2;
			}

		}else{
			$qry = $this->conn->query("UPDATE users set $data where id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
				foreach($_POST as $k => $v){
					if($k != 'id'){
						if(!empty($data)) $data .=" , ";
						if($this->settings->userdata('id') == $id)
							$this->settings->set_userdata($k,$v);
					}
				}
				if(!empty($_FILES['img']['tmp_name'])){
					if(!is_dir(base_app."uploads/avatars"))
						mkdir(base_app."uploads/avatars");
					$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
					$fname = "uploads/avatars/$id.png";
					$accept = array('image/jpeg','image/png');
					if(!in_array($_FILES['img']['type'],$accept)){
						$err = "Image file type is invalid";
					}
					if($_FILES['img']['type'] == 'image/jpeg')
						$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name']);
					elseif($_FILES['img']['type'] == 'image/png')
						$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name']);
					if(!$uploadfile){
						$err = "Image is invalid";
					}
					$temp = imagescale($uploadfile,200,200);
					if(is_file(base_app.$fname))
					unlink(base_app.$fname);
					$upload =imagepng($temp,base_app.$fname);
					if($upload){
						$this->conn->query("UPDATE `users` set `avatar` = CONCAT('{$fname}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}'");
						if($this->settings->userdata('id') == $id)
						$this->settings->set_userdata('avatar',$fname."?v=".time());
					}

					imagedestroy($temp);
				}

				return 1;
			}else{
				return "UPDATE users set $data where id = {$id}";
			}
			
		}
	}
	public function delete_users(){
		extract($_POST);
		$qry = $this->conn->query("DELETE FROM users where id = $id");
		if($qry){
			$this->settings->set_flashdata('success','User Details successfully deleted.');
			if(is_file(base_app."uploads/avatars/$id.png"))
				unlink(base_app."uploads/avatars/$id.png");
			return 1;
		}else{
			return false;
		}
	}
	function registration(){
		$resp = array('status' => 'failed', 'msg' => 'An error occurred');
		
		// Check if POST data is received
		if(empty($_POST)){
			$resp['msg'] = 'No data received.';
			return json_encode($resp);
		}
		
		// Validate required fields first
		if(empty($_POST['username']) || empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['password'])){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Please fill in all required fields (Username, First Name, Last Name, Password).';
			return json_encode($resp);
		}
		
		// Hash password
		$_POST['password'] = md5($_POST['password']);
		
		// Remove id from POST data to prevent it from being included in INSERT
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		unset($_POST['id']); // Explicitly remove id from POST array
		
		$username = isset($_POST['username']) ? $this->conn->real_escape_string($_POST['username']) : '';
		
		$data = "";
		$check = $this->conn->query("SELECT * FROM `users` where username = '{$username}' ".($id > 0 ? " and id!='{$id}'" : "")." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Username already exists.';
			return json_encode($resp);
		}
		
		// Fields to exclude from INSERT
		$exclude_fields = ['id'];
		$columns = array();
		$values = array();
		$data = ""; // For UPDATE statement
		
		foreach($_POST as $k => $v){
			// Skip excluded fields and arrays
			if(in_array($k, $exclude_fields) || is_array($v)) {
				continue;
			}
			$v_escaped = $this->conn->real_escape_string($v);
			
			// Build columns and values for INSERT
			$columns[] = "`{$k}`";
			$values[] = "'{$v_escaped}'";
			
			// Build data string for UPDATE
			if(!empty($data)) $data .= ", ";
			$data .= " `{$k}` = '{$v_escaped}' ";
		}
		
		if(empty($id)){
			// For new registration, use explicit column list to ensure id is not included
			$sql = "INSERT INTO `users` (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";
		}else{
			$sql = "UPDATE `users` set {$data} where id = '{$id}' ";
		}
		
		// Log SQL for debugging (remove in production)
		error_log("Registration SQL: " . $sql);
		
		$save = $this->conn->query($sql);
		if($save){
			$uid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(!empty($id))
				$resp['msg'] = 'User Details has been updated successfully';
			else
				$resp['msg'] = 'Your Account has been created successfully';

			if(!empty($_FILES['img']['tmp_name'])){
				if(!is_dir(base_app."uploads/avatars"))
					mkdir(base_app."uploads/avatars");
				$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
				$fname = "uploads/avatars/$uid.png";
				$accept = array('image/jpeg','image/png');
				if(!in_array($_FILES['img']['type'],$accept)){
					$resp['msg'] = "Image file type is invalid";
				}
				if($_FILES['img']['type'] == 'image/jpeg')
					$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name']);
				elseif($_FILES['img']['type'] == 'image/png')
					$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name']);
				if(!$uploadfile){
					$resp['msg'] = "Image is invalid";
				}
				$temp = imagescale($uploadfile,200,200);
				if(is_file(base_app.$fname))
				unlink(base_app.$fname);
				$upload =imagepng($temp,base_app.$fname);
				if($upload){
					$this->conn->query("UPDATE `users` set `avatar` = CONCAT('{$fname}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$uid}'");
				}
				imagedestroy($temp);
			}
			if(!empty($id)){
				$user = $this->conn->query("SELECT * FROM `users` where id = '{$id}' ");
				if($user->num_rows > 0){
					$res = $user->fetch_array();
					foreach($res as $k => $v){
						if(!is_numeric($k) && $k != 'password'){
							$$k = $this->settings->set_userdata($k, $v);
						}
					}
				}
			}
		}else{
			$resp['status'] = 'failed';
			$error_msg = $this->conn->error;
			$resp['msg'] = 'Registration failed: ' . ($error_msg ? $error_msg : 'Database error occurred');
			// Include SQL in response for debugging to help identify the issue
			$resp['sql'] = $sql;
			$resp['data_fields'] = array_keys($_POST); // Show what fields were being processed
		}
		if($resp['status'] == 'success' && isset($resp['msg']))
			$this->settings->set_flashdata('success', $resp['msg']);
		
		return json_encode($resp);
	}
	
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	case 'registration':
		// Prevent any output before JSON
		if(ob_get_level() > 0) {
			ob_clean();
		}
		header('Content-Type: application/json');
		echo $users->registration();
		exit;
	break;
	default:
		// echo $sysset->index();
		break;
}