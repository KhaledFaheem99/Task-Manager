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

		if ($this->session->userdata('role') !== 'admin') {
			show_error('You are not allowed to access this page', 403);
			exit;
		}

	}

	public function index () {
		$data    = [];
        $page    = isset($_GET['page']) ? (int) $_GET['page'] : 1;
		$limit   = 2;
		$filters = [
			'user_id' => $this->input->get('user_id'),
			'status'  => $this->input->get('status'),
		];
		$tasks                    = $this->taskService->getFilteredPaginatedTasks($filters, $page , $limit);
        $total_tasks_filtered     = $this->taskService->countFilteredTasks($filters);
		$total_pages              = ceil($total_tasks_filtered / $limit);
		$users                    = $this->taskService->getAllUsers();
		$countAllTasks            = $this->taskService->countAllTasks();
		$countOfCompleted         = $this->taskService->countOfCompleted();
		$countOverdue             = $this->taskService->countOverdue();

		$data['tasks']            = $tasks;
		$data['total_pages']      = $total_pages;
		$data['current_page']     = $page;
		$data['filters']          = $filters;
		$data['users']            = $users;
		$data['total_tasks']      = $countAllTasks;
		$data['completed_tasks']  = $countOfCompleted;
		$data['overdue_tasks']    = $countOverdue;
		$this->load->view('Task/admin/dashboard' , $data);
	}

	public function create () {
		$users       = $this->taskService->getAllUsers();
		$this->load->view('Task/admin/create_task' , ['users' => $users]);
	}


	public function valid_date($date) {
		$today = date('Y-m-d');

		if ($date < $today) {
			$this->form_validation->set_message('valid_date', 'The due date cannot be in the past.');
			return false;
		}

		return true;
	}

	public function store () {
		if ($this->input->method() == 'post') {
			$data        = $this->input->post();
			$response    = $this->taskService->createTask($data);
			$users       = $this->taskService->getAllUsers();

			if (!$response['status']) {
				$this->load->view('Task/admin/create_task' , [
					'users'  => $users ,
					'errors' => $response['error'],
				]);
				return;

			}else {
				redirect('dashboard');
				exit;
			}

		}else {
			redirect('tasks/create');
			exit;
		}
	}

	public function show ($id) {
		$task = $this->taskService->show($id);
		if (!$task){
			show_404();
			return;
		}
		$this->load->view('Task/admin/show_task' , ['task' => $task]);
	}

	public function edit ($id) {
		$task        = $this->taskService->show($id);
		$users       = $this->taskService->getAllUsers();
		if (!$task){
			show_404();
			return;
		}
		$this->load->view('Task/admin/edit_task' , ['task' => $task , 'users' => $users]);
	}

	public function update ($id) {
		if ($this->input->method() == 'post'){
			$data        = $this->input->post();
			$users       = $this->taskService->getAllUsers();
			$response    = $this->taskService->update($id , $data);

			if (!$response['status']) {
				$this->load->view('Task/admin/edit_task' , ['users' => $users , 'errors' => $response['error']]);
				return;
			}

				redirect('dashboard');
				exit;
			
		}else {
			redirect('tasks/edit/' . $id);
			exit;
		}
	}

	public function destroy ($id) {
		if ($this->input->method() == 'post'){

			$task        = $this->taskService->show($id);
			if (!$task){
				show_404();
				return;
			}
			$this->taskService->destroy($id);
			redirect('dashboard');
			exit;
		}else {
			redirect('dashboard');
			exit;
		}
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
				redirect('dashboard');
				exit;
		}else {
			redirect('dashboard');
			exit;
		}
	}
}
