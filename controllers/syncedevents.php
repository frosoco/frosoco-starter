<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syncedevents extends CI_Controller {

	/**
	 * Main route for events page
	 *
	 * Route: /syncedevents
	 */
	public function index()
	{

		$syncedevent = new Syncedevent();
		$syncedevent->where('event_start_date >', date('Y-m-d H:m:s'));
		$syncedevent->order_by('event_start_date', 'asc');
		$syncedevent->get();
		$data['syncedevents'] = $syncedevent;

		// Create the view
		$this->template->title = 'Synced Events';
		$this->template->javascript->add('assets/js/masonry.min.js');
		$this->template->content->view('syncedevents/index', $data);
		$this->template->publish();

	}

	/**
	 * View an event
	 *
	 * Route: /syncedevents/view/{id}
	 */
	public function view($id)
	{

		$this->load->library('markdown');

		$data['id'] = $id;
		$syncedevent = new Syncedevent($id);
		$data['syncedevent'] = $syncedevent;

		// Get all resident (even if they haven't responded to the form)
		$users = new User();
		$users->where('role = "resident" OR role = "staff"');
		$users->get();
		$data['users'] = $users;

		// Parse spreadsheet data as JSON
		$spreadsheetLink = 'https://spreadsheets.google.com/feeds/list/'.$syncedevent->google_responses_link.'/1/public/basic?alt=json';
		$jsonResponse = file_get_contents($spreadsheetLink);
		//$data['json_response'] = $jsonResponse;
		$data['json_response'] = count($users);
		$userResponseData = array();
		foreach($users as $user) {
			$userResponseData[$user->sunet] = array();
			$userResponseData[$user->sunet]['userData'] = $user;
			$userResponseData[$user->sunet]['responseData'] = NULL;
		}
		/*
		// Parse the responses and enter them into $userResponseData
		$responseList = json_decode($jsonResponse, true);
		$decodedList = $responseList['feed']['entry'];

		$rowCount = 0; // Current row being processed; this variable is not in use yet, but can be helpful for debugging.

		foreach ($decodedList as $entry){
			$rowCount++;
			// Get raw dateText and responseText for the current entry in the decoded JSON responses list.
	    $dateText = $entry['title']['$t'];
	    $responseText = $entry['content']['$t'];

	    $responseArray = array();
	    try {
	    	$responseArray['date'] = new DateTime($dateText);
	    }
	    catch (Exception $e) {
	    	$responseArray['date'] = NULL;
	    }

	    // Parse the keys and values from the responseText
	    $keyPattern = '/([^ :]*): (.*)/';
	    $valuePattern = '/(.+?(?=[^ :]*:))(.*)/';
	    while (preg_match($keyPattern, $responseText, $matches)){
	    	$key = $matches[1];
	    	$responseText = $matches[2]; // response = remainder after key
	    	$value = $responseText;
	    	if(preg_match($valuePattern, $responseText, $matches)){
	    		$responseText = $matches[2];// response = remainder after value
	    	} // else we've reached the end, and the $value was already set to the remainder after the last key
	    	$responseArray[$key] = $value;
	    }

	    // Check username validity before adding response to final userResponseData Array to be rendered in the view.
	    if (array_key_exists('username', $responseArray)) {
	    	// If contains valid email format
	    	if (preg_match('/([a-zA-Z0-9]+)@stanford.edu/', $responseArray['username'], $matches)){
	    		$username = $matches[1];
	    		// If valid parsed username
	    		if (array_key_exists($username, $userResponseData)) {
	    			// If user is actually found in FroSoCo USERS database
		    		if (is_null($userResponseData[$username]['responseData'])) {
		    			$userResponseData[$username]['responseData'] = $responseArray;
		    		} else { // If duplicate responses from same user found, only use the most recent one.
		    			$existingDate = $userResponseData[$username]['responseData']['date'];
		    			$newDate = $responseArray['date'];
		    			if (!is_null($newDate) and is_null($existingDate)){
		    				// Replace the old date if it is null and new date is not null
		    				$userResponseData[$username]['responseData'] = $responseArray;
		    			} else if (!is_null($newDate) and !is_null($existingDate) and $newDate > $existingDate) {
		    				// Replace the old date if both are not null but new date is more recent
		    				$userResponseData[$username]['responseData'] = $responseArray;
		    			}
		    		}
		    	}
	    	}
	    	// TODO: track bad data (invalid emails/ usernames/ dates)
	    }
		}
		*/
		$data['user_responses'] = $userResponseData;
		// $data['parsed_responses'] = [username1 -> ['userData' -> $user1, 'responseData -> [date -> v1, going -> v2, key3 -> v3, ..., keyn -> vn]], username2 -> ['userData' -> $user2, 'responseData'], ... ]

//		$data['json_response'] = (new DateTime($dateText))->format('l m/d/Y g:ia');

		// Create the view
		$this->template->title = $data['syncedevent']->name;
		$this->template->content->view('syncedevents/view', $data);
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

	// TODO(puzon): change so that all staff + seniorstaff can edit event
	private function canEdit($event) {
		$sessionUser = new User($this->session->userdata('id'));
		if ($sessionUser->id == $event->user_id) {
			return true;
		} return false;
	}


}
