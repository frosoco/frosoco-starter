<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Events extends CI_Controller {

	/**
	 * Main route for events page
	 *
	 * Route: /events
	 */
	public function index() 
	{

		$event = new Event();
		$event->where('start >', date('Y-m-d H:m:s'));
		$event->order_by('start', 'asc');
		$event->get();
		$data['events'] = $event;

		// Create the view
		$this->template->title = 'Events';		
		$this->template->javascript->add('assets/js/masonry.min.js');
		$this->template->content->view('events/index', $data);
		$this->template->publish();

	}

	/**
	 * View an event
	 *
	 * Route: /events/view/{id}
	 */
	public function view($id)
	{

		$this->load->library('markdown');

		$data['id'] = $id;
		$data['event'] = new Event($id);

		// Check whether you're signed up for the event already
		$signup = new Signup();
		$signup->where('user_id', $this->session->userdata('id'));
		$signup->where('event_id', $id);
		$signup->get();
		if ($signup->exists()) {
			$data['signedup'] = true;
		} else {
			$data['signedup'] = false;
		}

		// Get all of the people who are signed up
		$signups = new Signup();
		$signups->where('event_id', $id);
		$signups->get();
		$data['signups'] = $signups;

		// Create the view
		$this->template->title = $data['event']->name;
		$this->template->content->view('events/view', $data);
		$this->template->publish();

	}


	/**
	 * Create a new event, or saves an edited event
	 *
	 * Route: /events/save
	 */
	public function save($id = NULL)
	{

		// Get the POST parameters
		$e = new Event();
		if ($id != NULL) {
			$e = new Event($id);
		}

		$e->name = $this->input->post('event-name');
		$e->location = $this->input->post('event-location');
		
		$e->start = date("Y-m-d H:i:s", strtotime($this->input->post('event-start')));		
		$e->end = date("Y-m-d H:i:s", strtotime($this->input->post('event-end')));

		$e->user_id = $this->session->userdata('id');
		$e->description = $this->input->post('event-description');
		$e->capacity = $this->input->post('event-capacity');
		$e->open = 1;
		$e->save(); 

		// Redirect to page for that event
		header('Location: /events/view/' . $e->id);

	}

	/**
	 * Sign up for an event
	 *
	 * Route: /events/signup/
	 */
	public function signup() 
	{

		if (!$this->authorized()) {
			header('Location: /login');
		}	

		// Get the POST parameters
		$s = new Signup();
		$s->event_id = $this->input->post('event_id');
		$s->user_id = $this->session->userdata('id');
		$s->save();

		// Return JSON data for approved signup
		echo json_encode(array('success' => true));
 
	}

	public function edit($id = NULL)
	{

		if (!$this->authorized()) {
			header('Location: /login');
		}

		// Get the event
		$event = new Event($id);

		// Cannot edit event, go somewhere
		if (!$this->canEdit($event)) {
			header('Location: /event/view/' . $id);
		}

		$data['event'] = $event;

		// Create the view
		$this->template->title = 'Edit Event';
        $this->template->javascript->add('assets/js/markdown.js');
        $this->template->stylesheet->add('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.min.css');
        $this->template->stylesheet->add('assets/css/jquery-ui-timepicker-addon.css');
        $this->template->javascript->add('assets/js/jquery-ui-timepicker-addon.js');
		$this->template->content->view('events/edit', $data);
		$this->template->publish();		

	}

	/**
	 * Checks to see if a user is authorized based on session storage
	 */
	private function authorized() {
		if (!$this->session->userdata('id')) {
			return false;
		} return true;
	}

	private function canEdit($event) {
		$sessionUser = new User($this->session->userdata('id'));
		if ($sessionUser->id == $event->user_id) {
			return true;
		} return false;
	}


}
