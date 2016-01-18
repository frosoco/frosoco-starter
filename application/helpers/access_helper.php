<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

if (!function_exists('get_permissions')) {
  function get_permissions() {
    if (!isset($GLOBALS['FSC_PERMISSIONS'])) {
      $public = array(
        "home" => TRUE,
        "about" => TRUE,
        "people" => TRUE,
        "programs" => TRUE,
        "help" => array(
          "academic" => TRUE,
          "housing" => TRUE,
        ),
        "access_denied" => TRUE,
        "auth" => array(
          "index" => TRUE,
          "login" => TRUE,
        ),
        "event" => array(
          "get_events_by_date" => true,
        ),
      );

      $stanford = array_merge_recursive(array(), $public);

      $resident = array_merge_recursive(
        array(
          'auth' => array(
            'login' => FALSE,
            'logout' => TRUE,
          ),
          'event' => array(
            'view' => true,
          ),
          'sofo' => array(
            'index' => true,
            'view' => true,
            'read_sign_up' => true,
            'create_sign_up' => true,
            'delete_sign_up' => true,
          ),
          'calendar' => array(
            'index' => true,
          ),
          'sign_up' => array(
            'create' => true,
            'delete' => true,
          ),
          'user_directory' => array(
            'index' => true,
          ),
          'facebook' => array(
            'frosoco' => true
          ),
          'note' => array(
            'index' => true,
          ),
          'photo' => array(
            'index' => true,
            'upload' => true,
          ),
          "help" => array(
            "contact" => TRUE,
          ),
          'course' => array(
            'index' => true,
            'read_json_courses' => true,
            'read_json_courses_by_user' => true,
            'read_json_users_by_course' => true,
            'read_json_course_info' => true,
            'add_course_for_logged_in_user' => true,
            'remove_course_for_logged_in_user' => true,
          )
        ),
        $stanford
      );

      $staff = array_merge_recursive(
        array(
          'event' => array(
            'create' => true,
            'edit' => true,
            'delete' => true,
            'set_sign_up_is_open' => true,
            'delete_sign_up' => true,
          ),
          'sign_up' => array(
            'create' => true,
            'delete' => true,
            'view_by_event' => true,
            'view_csv_by_event' => true,
            'view_by_event' => true,
            'send_email' => true,
          ),
          'wiki' => array(
            'staff' => true,
            'basecamp' => true,
          ),
          'facebook' => array(
            'frosoco_staff' => true
          ),
          'flashcards' => array(
            'index' => true
          )
        ),
        $resident
      );

      $confi = array_merge_recursive(
        array(
          'wiki' => array(
            'confi' => true,
          ),
        ),
        $staff
      );

      $permissions = array(
        'PUBLIC' => $public,
        'STANFORD' => $stanford,
        'RESIDENT' => $resident,
        'STAFF' => $staff,
        'CONFI' => $confi,
      );

      $GLOBALS['FSC_PERMISSIONS'] = $permissions;
    }

    return $GLOBALS['FSC_PERMISSIONS'];
  }
}

if (!function_exists('can_do')) {
  function can_do($user_access_group, $action) {
    if ($user_access_group == 'SUDO') {
      return TRUE;
    }

    $permissions = get_permissions();

    if (!isset($permissions[$user_access_group][$action['class']])) {
      return FALSE;
    }
    $permission = $permissions[$user_access_group][$action['class']];
    if (is_bool($permission)) {
      return $permission;
    }

    if (!isset($permission[$action['method']])) {
      return FALSE;
    }
    $permission = $permission[$action['method']];
    if (is_array($permission)) {
      return $permission[0];
    }
    return $permission;
  }
}

if (!function_exists('is_action_public')) {
  function is_action_public($action) {
    return can_do('PUBLIC', $action);
  }
}
