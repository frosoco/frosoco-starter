<?php  if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

require_once(dirname(__FILE__) . "/access_helper.php");

session_start();

if (!function_exists('get_remote_user')) {
  function get_remote_user() {
    $CI =& get_instance();
    $CI->load->library('session');
    $is_set_session_user = $CI->session->userdata('user_id') != FALSE;

    if (!$is_set_session_user && !set_session_user()) {
      return null;
    }

    $user = array();
    $user['id'] = $CI->session->userdata('user_id');
    $user['sunetid'] = $CI->session->userdata('user_sunetid');
    $user['access_group'] = $CI->session->userdata('user_access_group');

    return $user;
  }
}

if (!function_exists('set_session_user')) {
  function set_session_user() {
    $CI =& get_instance();
    $CI->load->model('User_model');

    $user = array();

    $user['user_sunetid'] = "";
    if (isset($_SERVER['REMOTE_USER'])) {
      $user['user_sunetid'] = $_SERVER['REMOTE_USER'];
    } elseif (isset($_GET['user_id'])) {
      $user['user_sunetid'] = decrypt_user_id($_GET['user_id']);
    } elseif (ENVIRONMENT == "development") {
      if (isset($_SERVER['PHP_AUTH_USER'])) {
        $user['user_sunetid'] = $_SERVER['PHP_AUTH_USER'];
      } elseif (isset($_SESSION['REMOTE_USER'])) {
        $user['user_sunetid'] = $_SESSION['REMOTE_USER'];
      }
    }
    if ($user['user_sunetid'] === "") {
      return false;
    }

    $user['user_id'] =
      $CI->User_model->get_id_by_sunetid($user['user_sunetid']);
    $user['user_access_group'] =
      $CI->User_model->get_access_group($user['user_id']);
    $user['user_email'] =
      $CI->User_model->get_email($user['user_id']);

    if (!$user['user_access_group']) {
      $user['user_access_group'] = 'PUBLIC';
    }

    $CI->session->set_userdata($user);
    log_message(
      'info',
      'Session Initialized' .
      ' sunetid:' . $user['user_sunetid'] .
      ' user_access_group:' . $user['user_access_group'] .
      ' user_id:' . $user['user_id']
    );
    $log_msg =
      date('Ymd-H:i:s') .
      ' Session Initialized' .
      ' sunetid:' . $user['user_sunetid'] .
      ' user_access_group:' . $user['user_access_group'] .
      ' user_id:' . $user['user_id'];
    `echo $log_msg >> user_access.log`;
  }
}

if (!function_exists('decrypt_user_id')) {
  function decrypt_user_id($user_id) {
    include(dirname(__FILE__) . "/../../conf/config.php");

    $cipher_alg = MCRYPT_RIJNDAEL_128;
    $decrypted = "";
    for ($chunk_number = 0; $chunk_number < 3; $chunk_number++) {
      $chunk = substr($user_id, $chunk_number*24, 24);
      $decoded_chunk = base64_decode($chunk);
      $decrypted_string = @mcrypt_decrypt($cipher_alg, $SALT, $decoded_chunk, MCRYPT_MODE_CBC);
      $decrypted .= $decrypted_string;
    }

    $datetime_string = substr($decrypted, 0, 25);
    $username = trim(substr($decrypted, 25));

    $date = date("c");
    $date_diff = time() - strtotime($datetime_string);
    if ($date_diff >= 10) {
      return "";
    }

    return $username;
  }
}

if (!function_exists('remote_user_can_do')) {
  function remote_user_can_do($action) {
    $remote_user = get_remote_user();

    if ($remote_user == NULL) {
      $remote_user = array('access_group' => 'PUBLIC');
    }

    return can_do($remote_user['access_group'], $action);
  }
}
