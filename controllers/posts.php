<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends CI_Controller {

	/*
	 * Retrieves the latest posts from all users.
	 *
	 * Location: /posts
	 */
	public function index() {
		
		$post = new Post();
		$data['posts'] = $post->get();

		// Create the view
		$this->template->title = 'Posts';
		$this->template->javascript->add('assets/js/masonry.min.js');
		$this->template->content->view('posts/index', $data);
		$this->template->publish();	

	}

	public function edit() {

	}

	public function view($id = null) {

		$post = new Post($id);
		$this->load->library('markdown');

		$data['title'] = $post->title;
		$data['author'] = $post->user->get();
		$data['post'] = $this->markdown->parse($post->text);

		// Create the view
		$this->template->title = $post->title;
		$this->template->content->view('posts/view', $data);
		$this->template->publish();	

	}

	public function add() {

		$post = new Post();
		$post->user_id = $this->session->userdata('id');
		$post->title = $this->input->post('post-title');
		$post->text = $this->input->post('post-body');
		$post->save();

		// Redirect to view mode
		header('Location: /posts/view/' . $post->id);

	}

}

/* End of file posts.php */
/* Location: ./application/controllers/posts.php */