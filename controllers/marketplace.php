<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketplace extends CI_Controller {

	public function index()
	{

		$item = new Item();
		$data['listings'] = $item->get();

		// Create the view
		$this->template->title = 'Listings';
		$this->template->content->view('marketplace/index', $data);
		$this->template->publish();	

	}

	public function add()
	{
		$item = new Item();
		$item->user_id = $this->session->userdata('id');
		$item->item = $this->input->post('listing-title');
		$item->description = $this->input->post('listing-body');
		$item->asking = $this->input->post('listing-asking');
		$item->save();

		// Redirect to view mode
		header('Location: /marketplace');
	}
}

?>