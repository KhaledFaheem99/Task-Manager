<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AuthService {
	protected $CI;
	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('form_validation');
		$this->CI->load->model('User_model');
	}
	public function createUser ($data) {
		$this->CI->form_validation->set_data($data);
        $this->CI->form_validation->set_rules('username', 'UserName', 'required|min_length[3]|max_length[20]|is_unique[users.username]');
        $this->CI->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->CI->form_validation->set_rules('passwordConfirmation', 'Confirm Password', 'required|matches[password]');

		if (!$this->CI->form_validation->run()) {
			return ['status' => false , 'errors' => $this->CI->form_validation->error_array()];
		}

		$userData  = [
		'username' => $data['username'],
		'password' => password_hash($data['password'] , PASSWORD_DEFAULT),
		];

		$this->CI->User_model->insertUser($userData);
		return ['status' => true];
	}

	public function getUser ($userName) {
		return $this->CI->User_model->getUserByUserName($userName);
	}

	public function loginUser ($data) {
		$this->CI->form_validation->set_data($data);
		$this->CI->form_validation->set_rules('username', 'UserName', 'required');
		$this->CI->form_validation->set_rules('password', 'Password', 'required');

		if (!$this->CI->form_validation->run()){
			return ['status' => false , 'errors' => $this->CI->form_validation->error_array()];
		}

		$user = $this->CI->User_model->getUserByUserName($data['username']);
		if (!$user) {
			return ['status' => false , 'errors' => ['username' => 'User not found']];
		}

		if (!password_verify($data['password'] , $user->password)){
			return ['status' => false , 'errors' => ['password' => 'Incorrect password']];
		}

		unset($user->password);
		return ['status' => true , 'user' => $user];
	}

}
