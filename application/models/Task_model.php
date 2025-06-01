<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Task_model extends CI_Model {
	public function __construct() {
		parent::__construct();
        $this->load->database();
	}

    protected $table = 'tasks';

	public function insertTask ($data) {
		return $this->db->insert($this->table , $data);
	}

	public function countAllTasks () {
    	return $this->db->count_all('tasks');
	}

	public function getTaskById ($id) {
		$query = $this->db->select('tasks.* , users.username AS assigned_user')
		->from('tasks')
		->join('users' , 'tasks.assigned_to = users.id')
		->where('tasks.id' , $id)->get();
		return $query->row();
	}

	public function updateTask ($id , $data) {
		$this->db->where('id' , $id);
		$this->db->update($this->table , $data);
	}

	public function deleteTask ($id) {
		$this->db->where('id' , $id);
		$this->db->delete($this->table);
	}

	public function changeStatus ($id , $status) {
			$this->db->where('id' , $id);
			$this->db->update($this->table, ['status' => $status]);
	}

	public function getCountOfCompleted () {
		$this->db->where('status', 'completed');
		return $this->db->count_all_results($this->table);
	}

	public function countOverdue () {
		return $this->db->where('due_date <' , date('Y-m-d'))->where('status !=' , 'completed')->count_all_results($this->table);
	}

	public function getTasksOverdueByUserId ($userID) {
		if (!$userID) {
			return false;
		}
			return $this->db
				->where('due_date <' , date('Y-m-d'))
				->where('status !='  , 'completed')
				->where('assigned_to', $userID)
				->get($this->table)
				->result();
	}

	public function getFilteredPaginatedTasks ($filters , $limit , $offset) {
		$this->db->select('tasks.* , users.username AS assigned_user');
		$this->db->from('tasks');
		$this->db->join('users' , 'tasks.assigned_to = users.id');
		$this->db->order_by('id' , 'DESC');

		if (!empty($filters['user_id'])){
			$this->db->where('assigned_to' , $filters['user_id']);
		}

		if (!empty($filters['status'])){
			$this->db->where('status' , $filters['status']);
		}

		$this->db->limit($limit , $offset);
		$query = $this->db->get();
		return $query->result();
	}

	public function countFilteredTasks ($filters) {
		$this->db->from('tasks');

		if (!empty($filters['user_id'])) {
			$this->db->where('assigned_to', $filters['user_id']);
		}

		if (!empty($filters['status'])) {
			$this->db->where('status', $filters['status']);
		}

		return $this->db->count_all_results();
	}

	public function filterAndPaginateUserTasks ($filter , $limit , $offset , $userID) {
		$this->db->select('tasks.*');
		$this->db->where('assigned_to' , $userID);
		$this->db->order_by('id' , 'DESC');

		if (!empty($filter['status'])) {
			$this->db->where('status' , $filter['status']);
		}

		$this->db->limit($limit , $offset);
		$query = $this->db->get($this->table);
		return $query->result();
	}

	public function countFilterdUserTasks ($filter , $userID) {
		$this->db->from('tasks');
		$this->db->where('assigned_to', $userID);
	
		if (!empty($filter['status'])) {
			$this->db->where('status' , $filter['status']);
		}

		return $this->db->count_all_results();
	}
}
