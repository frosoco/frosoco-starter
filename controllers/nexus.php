<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nexus extends CI_Controller {

	private function authorized() {
		if (!$this->session->userdata('id')) {
			return false;
		} return true;
	}

	function getStartAndEndDate($week, $year)
	{
	    $time = strtotime("1 January $year", time());
	    $day = date('w', $time);
	    $time += ((7*$week)+1-$day)*24*3600;
	    $return[0] = date('Y-n-d', $time);
	    $time += 6*24*3600;
	    $return[1] = date('Y-n-d', $time);
	    return $return;
	}

	public function index() {

		$this->template->title = 'Nexus';
		$this->template->content->view('nexus/index');
		$this->template->publish();

	}

	public function roster() {

		// Check for POST data on request
		$data['users'] = array();
		$data['group_size'] = 1;
		$postData = $this->input->post(NULL, TRUE);
		if ($postData) {
			$users = new User();
			if ($this->input->post('freshmen')) {
				$users->where('year','2017');
			} if ($this->input->post('sophomores')) {
				$users->where('year','2016');
			} if ($this->input->post('staff')) {
				$users->where('role','staff');
			} $users->order_by('id', 'random');
			$users->get();
			if ($this->input->post('size')) {
				$data['group_size'] = (int)$this->input->post('size');
			} $data['users'] = $users;
		}

		$this->template->title = 'Roster Generator';
		$this->template->content->view('nexus/roster', $data);
		$this->template->publish();

	}

	public function announce() {

		$this->template->title = 'Announce';
        $this->template->javascript->add('assets/js/markdown.js');
		$this->template->content->view('nexus/announce');
		$this->template->publish();

	}

	public function sociogram() {

		// Check that the current user is authorized
		if (!$this->authorized()) {
			header('Location: /auth/login');
		}

		// Check if this is a POST request
		$postData = $this->input->post(NULL, TRUE);

		if ($postData) {

			// Get the data strings from the input
			$nodes = $this->input->post('nodes');
			$edges = $this->input->post('edges');

			// Save it to the current user
			$current_user = new User();
			$current_user->where('id', $this->session->userdata('id'))->get();
			$current_user->nodes = $nodes;
			$current_user->edges = $edges;
			$current_user->save();

			// Return a success
			return $nodes;

		}

		// Check for a load parameter with the GET request
		if ($this->input->get('load') == 'true') {

			// Return the necessary things in a JSON array
			$current_user = new User();
			$current_user->where('id', $this->session->userdata('id'))->get();

			print $current_user->nodes;
			print $current_user->edges;

			$arr = array();
			array_push($arr, $current_user->nodes);
			array_push($arr, $current_user->edges);

			$this->output->set_content_type('application/json')->set_output(json_encode($arr));
			return;

		}

		// Otherwise just load it
		$users = new User();
		$users->get();
		$data['users'] = $users;
		$this->load->view('nexus/sociogram', $data);
	}

	public function send_newsletter() {

		$this->load->library('markdown');

		$currentWeek = date("W");
		$currentYear = date("Y");

		$dates = $this->getStartAndEndDate($currentWeek, $currentYear);

		// Generate the message
		$event = new Event();
		$event->where('WEEKOFYEAR(start) =', $currentWeek);
		$event->order_by('start', 'asc');
		$event->get();
		$data['dates'] = $dates;
		$data['events'] = $event;
		$message = $this->load->view('email/newsletter',$data,TRUE); 


		$this->load->library('email');
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from('fsc-announce@fsc.stanford.edu', 'FSC Nexus');
		$this->email->subject('Weekly Newsletter for ' . $dates[0] . ' to ' . $dates[1]);
		$this->email->to($this->input->post('email'));
		$this->email->message($message);
		$this->email->send();

	}

	public function send_announcement() {

		$this->load->library('email');

		$this->email->from($this->input->post('email'),'Roger Chen (via fsc-announce)');

		$this->email->subject('Testing email');
		$this->email->message('Testing the email class on this system');

		$users = new User();
		$users->get();

		$inputs = $this->input->post(NULL, TRUE);

		$targets = $this->input->post('targets');

		$residents = in_array("residents", $targets);
		$staff = in_array("staff", $targets);
		$seniorstaff = in_array("seniorstaff", $targets);

		$send_targets = array();

		foreach ($users as $user) {

			if ($user->role == 'resident' && $residents) {
				array_push($send_targets, $user->getEmail());
			}

			if ($user->role == 'staff' && $staff) {
				array_push($send_targets, $user->getEmail());
			}

		}

		$this->email->to(implode(",", $send_targets));

		$this->email->send();

	}

	

}