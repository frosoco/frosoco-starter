<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotes extends CI_Controller {

	public function view($id = NULL) {

		$data['quote'] = new Quote($id);

	}

	public function index() {

		$quote = new Quote();
		// hacky way to view most recent quotes first (prefer timestamps for future)
		$quote->order_by("id", "desc");
		$data['quotes'] = $quote->get();

		// Create the view
		$this->template->title = 'Quotes';
		$this->template->javascript->add('assets/js/masonry.min.js');
		$this->template->content->view('quotes/index', $data);
		$this->template->publish();

	}

	public function add() {

		$quote = new Quote();
		$quote->user_id = $this->session->userdata('id');
		$quote->text = $this->input->post('body');
		$quote->author = $this->input->post('author');
		$quote->save();

		header('Location: /quotes');

	}

}