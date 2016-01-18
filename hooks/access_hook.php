<?php  if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Access_hook {
  private static $CI;
  private static $base_url;

  function __construct() {
    self::$CI =& get_instance();
    self::$base_url =
      $GLOBALS['CFG']->config['base_url'];
  }

  public static function post_controller_constructor() {
    self::$CI->load->helper('access');
    self::$CI->load->helper('remote_user');

    $routing =& load_class('Router');
    $class = $routing->fetch_class();
    $method = $routing->fetch_method();
    $action = array(
      'class' => $class,
      'method' => $method,
    );

    $remote_user = get_remote_user();

    if ($remote_user !== NULL && isset($_GET["user_id"])) {
      self::redirect_self();
    }

    if (is_action_public($action)) {
      return true;
    }

    if ($remote_user == NULL && !is_action_public($action)) {
      self::redirect_login();
      return false;
    }

    if (can_do($remote_user['access_group'], $action)) {
      return true;
    }

    self::redirect_access_denied();
    return false;
  }

  private static function redirect_access_denied() {
    header("Location: " . self::$base_url . $GLOBALS['CFG']->config['index_page'] .
      "/access_denied");
  }

  private static function redirect_login() {
    header("Location: " . self::$base_url . "auth/index.php?referrer=" . urlencode($_SERVER['REQUEST_URI']));
  }

  private static function redirect_home() {
    header("Location: " . self::$base_url);
  }

  private static function redirect_self() {
    header("Location: " . $_SERVER["PHP_SELF"]);
  }

}
