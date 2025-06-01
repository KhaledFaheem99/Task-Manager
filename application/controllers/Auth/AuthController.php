<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property CI_Loader  $load
 * @property CI_Input   $input
 * @property CI_Session $session
 */
class AuthController extends CI_Controller {
	protected $authService;
	public function __construct() {
		parent::__construct();
		$this->load->library('../Services/Auth/AuthService');
		$this->load->library('session');
		$this->authService = new AuthService();
	}

	public function registerView () {
		$this->load->view('Auth/register');
	}

	public function store () {
        if ($this->input->method() == 'post') {

			$data        = $this->input->post();
			$response    = $this->authService->createUser($data);

			if (!$response['status']) {
            $this->load->view('Auth/register', ['errors'  => $response['errors']]);
			}else {
            $this->load->view('Auth/register', ['success' => 'Register Has Been Successfully U Will Be Redirect To Login Page After 5 Seconds']);
			}

		}else {
			$this->load->view('Auth/register');
		}
	}

	public function loginView () {
		$this->load->view('Auth/login');
	}

	public function login () {
		if ($this->input->method() == 'post') {
			$credentials = $this->input->post(); 
			$response    = $this->authService->loginUser($credentials);

			if (!$response['status']) {
				$this->load->view('Auth/login' , ['errors' => $response['errors']]);
			}

			$this->session->set_userdata([
				'user_id'  => $response['user']->id,
				'username' => $response['user']->username,
				'role'     => $response['user']->role
			]);

			if ($response['user']->role == 'admin') {
				redirect('dashboard');
				exit;
			}else {
				redirect('user_dashboard');
				exit;
			}
		}
	}

	public function logout () {
		$this->session->sess_destroy();
		redirect('login');
		exit;
	}
}
