<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	// Static pages
class Home extends CI_Controller {

	/**
	 * Index page for this controller.
	 *
	 * URL: /
	 */
	public function index()
	{
		$this->template->title = 'Home';
		$this->template->content->view('static/home');
		$this->template->publish();
	}

	// page with calendar of events
	// URL: /home/staff
	public function events()
	{
		$this->template->title = 'Events';
		$this->template->content->view('static/events');
		$this->template->publish();
	}

	// Alternate homepage used for under construction times
	private function under_construction()
	{
		$this->template->title = 'Under Construction';
        $this->template->stylesheet->add('assets/css/construction.css');
		$this->template->content->view('maintenence/under_construction');
		$this->template->publish();
	}

	/**
	 * Housing information.
	 *
	 * URL: /home/housing
	 */
	public function housing()
	{
		$this->template->title = 'Housing';
		$this->template->content->view('static/housing');
		$this->template->publish();
	}

	/**
	 * Event Programming information.
	 *
	 * URL: /home/programming
	 */
	public function programming()
	{
		$this->template->title = 'Programming';
		$this->template->content->view('static/programming');
		$this->template->publish();
	}

	/**
	 * Academic resources information.
	 *
	 * URL: /home/academic
	 */
	public function academic()
	{
		$this->template->title = 'Academic';
		$this->template->content->view('static/academic');
		$this->template->publish();
	}


	/**
	 * Housing front desk info.
	 *
	 * URL: /home/hfd
	 */
	public function hfd()
	{
		$this->template->title = 'HFD';
		$this->template->content->view('static/hfd');
		$this->template->publish();
	}

	/**
	 * Sophomore Seminar info
	 *
	 * URL: /home/sosem
	 */
	public function sosem()
	{
		$this->template->title = 'Sophomore Seminar';
		$this->template->content->view('static/sosem');
		$this->template->publish();
	}


	/**
	 * Location information.
	 *
	 * URL: /home/dining
	 */
	public function dining()
	{
		$this->template->title = 'Dining';
		$this->template->content->view('static/dining');
		$this->template->publish();
	}

	/**
	 * Staff information
	 *
	 * URL: /home/staff
	 */
	public function staff()
	{
		$staff_members = new User();
		$staff_members->where('role = "staff"');
		foreach ($staff_members as $staff) {
			echo $staff->first_name;
		}
		$staff_members->order_by("last_name", "asc");
		$staff_members->order_by("first_name", "asc");
		$staff_members->get();
		$data['staff_members'] = $staff_members;

		$this->template->title = 'Student Staff';
		$this->template->content->view('static/staff', $data);
		$this->template->publish();
	}

	/**
	 * Senior staff information
	 *
	 * URL: /home/seniorstaff
	 */
	public function seniorstaff()
	{
		$this->template->title = 'Senior Staff';
		$this->template->content->view('static/seniorstaff');
		$this->template->publish();
	}

	/**
	 * Arbitrary photos and stuff
	 *
	 * URL: /home/photos
	 */
	public function photos()
	{
		$this->template->title = 'Photos';
		$this->template->content->view('static/photos');
		$this->template->publish();
	}



	/**
	 *
	 *
	 * URL: /home/admitweekend
	 */
	public function admitweekend()
	{
		$this->template->title = 'Admit Weekened';
		$this->template->content->view('static/admit_weekend');
		$this->template->publish();
	}



	public function faq()
	{
		$this->template->title = 'FAQ';
		$this->template->content->view('static/faq');
		$this->template->publish();
	}


	/**
	 * Info on 2014-2015 in house draw
	 *
	 * URL: /home/inhousedraw
	 */
	public function inhousedraw()
	{
		// authenticate first
		if (!$this->authorized()) {
			header('Location: /auth/login');
		}

		$this->template->title = 'In-House Draw';
		$this->template->content->view('static/ihd');
		$this->template->publish();
	}

	/**
	 * Info on 2015-2016 dorm policies
	 *
	 * URL: /home/policies
	 */
	public function policies()
	{
		/*// authenticate first
		if (!$this->authorized()) {
			header('Location: /auth/login');
		}*/

		$this->template->title = 'Dorm Policies';
		$this->template->content->view('static/policies');
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
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
