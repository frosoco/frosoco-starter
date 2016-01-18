<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyLogin extends CI_Controller {

  function __construct()
  {
      parent::__construct();
      $this->load->model('user','',TRUE);
  }

  function index()
  {
      //This method will have the credentials validation
      $this->load->library('form_validation');

      $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|callback_check_database');

      if($this->form_validation->run() == FALSE)
      {
          //Field validation failed.  User redirected to login page
          $this->load->view('login_view');
      }
      else
      {
          //Go to private area
          header('Location: /');
      }
  }

  function check_database($username)
  {
      //Field validation succeeded.  Validate against database
      //$username = $this->input->post('username');

      //query the database
      $result = false;
      $this -> db -> select('id, first_name, sunet, role');
      $this -> db -> from('users');
      $this -> db -> where('sunet', $username);
      $this -> db -> limit(1);
      $query = $this -> db -> get();

      if($query -> num_rows() == 1)
      {
          $result = $query->result();
      }
      else
      {
          $result = false;
      }

      if($result)
      {
          $sess_array = array();
          foreach($result as $row)
          {
            /*
              $sess_array = array(
                  'id' => $row->id,
                  'username' => $row->username
              );
              $this->session->set_userdata('logged_in', $sess_array);
              */
              // Keep the user in our session
              $this->session->set_userdata('id', $row->id);
              $this->session->set_userdata('first_name', $row->first_name);
              $this->session->set_userdata('role', $row->role);
          }
          return TRUE;
      }
      else
      {
          $this->form_validation->set_message('check_database', 'Invalid username');
          return false;
      }
  }
}
?>