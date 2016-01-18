<?

class User extends DataMapper {

	var $has_many = array('event', 'upload', 'signup', 'post', 'quote', 'item',
		'course' => array(
			'class' => 'course',
			'other_field' => 'user',
			'join_self_as' => 'user',
			'join_other_as' => 'course',
			'join_table' => 'users_courses'
			)
		);

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	function getPhoto() {
		$pic = new Upload($this->photo_id);
		if ($pic->exists()) {
			return $pic->url;
		} else {
			return '/assets/images/default.jpg';
		}
	}

	function getEmail() {
		return $this->sunet . '@stanford.edu';
	}

	function getName() {
		return $this->first_name . ' ' . $this->last_name;
	}

	function getLocation() {
		return ucfirst($this->house . ' ' . $this->room);
	}

	function getYear() {
		return $this->year;
	}

	function isStaff() {
		// testing
		if (strstr($this->role, 'staff')) {
			return strstr($this->role, 'staff');
		}

	}

}

/* End of file user.php */
/* Location: ./application/models/user.php */
