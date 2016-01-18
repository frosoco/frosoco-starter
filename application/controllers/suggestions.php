<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suggestions extends CI_Controller {

	/*
	 * Retrieves the latest suggestions from all users.
	 *
	 * Location: /suggestions
	 */
	public function index() {
		
		$suggestion = new Suggestion();
		$data['suggestions'] = $suggestion->get();

		// Create the view
		$this->template->title = 'Suggestions';
		$this->template->content->view('suggestions/index', $data);
		$this->template->publish();	

	}

	public function add() {

		$suggestion = new Suggestion();
		$suggestion->user_id = $this->session->userdata('id');
		$suggestion->text = $this->input->post('suggestion-body');
		$suggestion->save();

		// Redirect to view mode
		header('Location: /suggestions');

	}

}

/* End of file posts.php */
/* Location: ./application/controllers/posts.php */