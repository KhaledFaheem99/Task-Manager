<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property CI_Loader  $load
 * @property CI_Input   $input
 * @property CI_Output  $output
 */
class TaskController extends CI_Controller {
    protected $taskService;
	public function __construct() {
        parent::__construct();
        $this->load->library('../Services/Task/TaskService');
        $this->taskService = new TaskService();
    }

	public function tasksOverdue () {
        $userID = $this->input->get('assigned_to');
        $tasks  = $this->taskService->getTasksOverdueByUserId($userID);

		if (!$userID) {
			$response = [
				'status'  => false,
				'message' => 'User Not Found',
			];

			return $this->output
				->set_status_header(404)
				->set_content_type('application/json')
				->set_output(json_encode($response));
		}

            $response = [
                'status' => 'Success Fetch',
                'tasks'  => $tasks,
            ];

            return $this->output
				->set_status_header (200)
                ->set_content_type ('application/json')
                ->set_output (json_encode($response));
    }
}
