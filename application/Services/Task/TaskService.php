<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TaskService {
	protected $CI;
	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library ('form_validation');
		$this->CI->load->model   ('Task_model');
		$this->CI->load->model   ('User_model');
	}

	public function getAllUsers () {
		return $this->CI->User_model->getAllUsers();
	}

	public function getAllTasks () {
		return $this->CI->Task_model->getAllTasks();
	}

	public function countAllTasks () {
		return $this->CI->Task_model->countAllTasks();
	}

	public function countOfCompleted () {
		return $this->CI->Task_model->getCountOfCompleted();
	}

	public function countOverdue () {
		return $this->CI->Task_model->countOverdue();
	}

	public function createTask ($data) {
		$this->CI->form_validation->set_data($data);
		$this->CI->form_validation->set_rules('title'      , 'Task Title'       , 'required|min_length[3]|max_length[100]');
		$this->CI->form_validation->set_rules('description', 'Task Description' , 'required|min_length[5]|max_length[500]');
		$this->CI->form_validation->set_rules('assigned_to', 'Assigned User'    , 'required|integer');
		$this->CI->form_validation->set_rules('due_date'   , 'Due Date'         , 'required|callback_valid_date');

		if (!$this->CI->form_validation->run()){
			return ['status' => false , 'error' => $this->CI->form_validation->error_array()];
		}

		$this->CI->Task_model->insertTask($data);
		return['status' => true];
	}

	public function show ($id) {
		return $this->CI->Task_model->getTaskById($id);
	}

	public function update ($id , $data) {
		$this->CI->form_validation->set_data($data);
		$this->CI->form_validation->set_rules('title', 'Task Title', 'required|min_length[3]|max_length[100]');
		$this->CI->form_validation->set_rules('description', 'Task Description', 'required|min_length[5]|max_length[500]');
		$this->CI->form_validation->set_rules('assigned_to', 'Assigned User', 'required|integer');
		$this->CI->form_validation->set_rules('due_date', 'Due Date', 'required|callback_valid_date');
		if (!$this->CI->form_validation->run()) {
			return ['status' => false , 'error' => $this->CI->form_validation->error_array()];
		}
		$this->CI->Task_model->updateTask($id , $data);
		return ['status' => true];
	}

	public function destroy ($id) {
		return $this->CI->Task_model->deleteTask($id);
	}

	public function status ($id , $status) {
		return $this->CI->Task_model->changeStatus($id , $status);
	}

    public function getTasksOverdueByUserId ($userID) {
        return $this->CI->Task_model->getTasksOverdueByUserId($userID);
    }

	public function getFilteredPaginatedTasks ($filters , $page , $limit) {
		$offset = ($page - 1) * $limit;
		return $this->CI->Task_model->getFilteredPaginatedTasks($filters , $limit , $offset);
	}

	public function countFilteredTasks ($filters) {
		return $this->CI->Task_model->countFilteredTasks($filters);
	}

	public function filterAndPaginateUserTasks ($filter , $limit , $current_page , $userID) {
		$offset = ($current_page - 1) * $limit;
		return $this->CI->Task_model->filterAndPaginateUserTasks($filter , $limit , $offset , $userID);
	}

	public function countFilterdUserTasks ($filter , $userID) {
		return $this->CI->Task_model->countFilterdUserTasks($filter , $userID);
	}
}
