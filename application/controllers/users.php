<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

	/**
	 * Main route for users page
	 *
	 * Route: /users
	 */
	public function index()
	{

		if (!$this->authorized()) {
			header('Location: /auth/login');
		}

		$users = new User();
		$users->where('role = "resident" OR role = "staff"');
		$users->order_by("last_name", "asc");
		$users->order_by("first_name", "asc");
		$users->get();
		$data['users'] = $users;

    $this->template->title = 'Users';
		$this->template->javascript->add('assets/js/isotope.min.js');
	 	$this->template->content->view('users/index', $data);

		$this->template->publish();
	}

	/**
	 * View a user
	 *
	 * Route: /users/view/{id}
	 */
	public function view($id)
	{

		if (!$this->authorized()) {
			header('Location: /auth/login');
		}

		$user = new User($id);
		$data['user'] = $user;
		$data['profile_pic'] = $user->getPhoto();

        $this->template->title = $user->first_name . ' ' . $user->last_name;
        $this->template->javascript->add('assets/js/vendor/jquery.ui.widget.js');
        $this->template->javascript->add('assets/js/jquery.iframe-transport.js');
        $this->template->javascript->add('assets/js/jquery.fileupload.js');
 		$this->template->content->view('users/view', $data);
		$this->template->publish();

	}

	/**
	 * Edit a user -- deprecated; must edit directly through database
	 *
	 * Route: /users/edit/{id}
	 */
	public function edit($id)
	{

		if (!$this->authorized() && (strstr($this->session->userdata('role'), 'staff') || $id = $this->session->userdata('id'))) {
			header('Location: /auth/login');
		}

		$user = new User($id);
		$data['user'] = $user;
		$data['profile_pic'] = $user->getPhoto();

        $this->template->title = $user->first_name . ' ' . $user->last_name;
        $this->template->javascript->add('assets/js/vendor/jquery.ui.widget.js');
        $this->template->javascript->add('assets/js/jquery.iframe-transport.js');
        $this->template->javascript->add('assets/js/jquery.fileupload.js');
 		$this->template->content->view('users/view', $data);
		$this->template->publish();

	}


	/**
	 * View a user
	 *
	 * Route: /users/flashcards
	 */
	public function flashcards()
	{

		if (!$this->authorized()) {
			header('Location: /auth/login');
		}
		$user = new User();
		//$user->where('role !=', 'staff');
		//$user->where('role !=', 'seniorstaff');
		$user->get();

		$data['people'] = $user;

        $this->template->title = 'Flashcards';
        $this->template->javascript->add('assets/js/jquery.flippy.min.js');
 		$this->template->content->view('users/flashcards', $data);
		$this->template->publish();

	}

	/**
	 * Used as the action target for a profile picture upload
	 */
	public function upload_profile()
	{

		// Set the upload path for the profile pictures
		$config['upload_path'] = './uploads/profiles';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['file_name'] = uniqid() . '.jpg';
		$this->upload->initialize($config);

		// Check to see if it is an upload
		if (!$this->upload->do_upload())
		{
			echo $this->upload->display_errors();
		}

		else
		{

			$data = $this->upload->data();

			$upload = new Upload();
			$upload->url = '/uploads/profiles/' . $data['file_name'];
			$upload->user_id = $this->input->post('userid');
			$upload_id = $upload->save();

			$user = new User();
			$user->get_by_id($upload->user_id);
			$user->photo_id = $upload->id;
			$user->save();

			header('Location: /users/view/' . $upload->user_id);

		}

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
