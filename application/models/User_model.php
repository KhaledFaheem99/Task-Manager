<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model {
	public function __construct() {
		parent::__construct();
        $this->load->database();
	}

    protected $table = 'users';

    public function insertUser($data) {
        return $this->db->insert($this->table, $data);
    }

	public function getUserByUserName ($username) {
		return $this->db->get_where('users' , ['username' => $username])->row();
	}

	public function getAllUsers () {
		$this->db->where('role' , 'user');
		$query = $this->db->get('users');
		return $query->result();
	}

}
