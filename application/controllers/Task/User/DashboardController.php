<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property CI_Loader $load
  * @property CI_Input $input
	* @property CI_Form_validation $form_validation
	  * @property CI_Session $session
 */
class DashboardController extends CI_Controller {
	protected $taskService;

	public function __construct() {
		parent::__construct();
		$this->load->library('../Services/Task/TaskService');
		$this->load->library('session');
		$this->taskService = new TaskService();

		if (!$this->session->userdata('user_id')){
			redirect('login');
			exit;
		}

		if ($this->session->userdata('role') !== 'user'){
			show_error('You are not allowed to access this page', 403);
			exit;
		}
	}

	public function index () {
		$data                  = [];
		$userID                = $this->session->userdata('user_id');
		$filter                = ['status' => $this->input->get('status')];
		$limit                 = 2;
        $current_page          = isset($_GET['page']) ? (int) $_GET['page'] : 1;
		$countFilterdUserTasks = $this->taskService->countFilterdUserTasks($filter , $userID);
		$total_pages           = ceil($countFilterdUserTasks / $limit);
		$tasks                 = $this->taskService->filterAndPaginateUserTasks($filter , $limit , $current_page , $userID);
		$data['tasks']         = $tasks;
		$data['total_pages']   = $total_pages;
		$data['filter']        = $filter;
		$data['current_page']  = $current_page;
		$this->load->view('Task/user/user_dashboard', $data);
	}

	public function status ($id) {
		if ($this->input->method() == 'post') {
			$task  = $this->taskService->show($id);
			if (!$task){
				show_404();
				return;
			}

			$status = $this->input->post('status');
			$this->taskService->status($id , $status);
				redirect('user_dashboard');
				exit;
		}else {
			redirect('user_dashboard');
			exit;
		}
	}

	public function show ($id) {
		$task = $this->taskService->show($id);
		if (!$task){
			show_404();
			return;
		}
		$this->load->view('Task/user/show_task' , ['task' => $task]);
	}
}
