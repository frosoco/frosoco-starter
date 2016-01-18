<?

class Event extends DataMapper {

	var $has_one = array('user');
	var $has_many = array('signup');
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	function getExcerpt()
	{
		if (strlen($this->description) > 300) {
			return substr($this->description, 0, 300) . '...';
		} else {
			return $this->description;
		}
	}

	function getStart()
	{
		return date('Y-m-d g:i A', strtotime($this->start));
	}

	function getEnd()
	{
		return date('Y-m-d g:i A', strtotime($this->end));
	}

	function getSignups()
	{
		// Get all of the people who are signed up
		$signups = new Signup();
		return $signups->where('event_id', $this->id)->count();
	}

}

/* End of file event.php */
/* Location: ./application/models/event.php */
