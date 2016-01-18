<?

class Syncedevent extends DataMapper {

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
		return date('Y-m-d g:i A', strtotime($this->event_start_date));
	}

	function getEnd()
	{
		return date('Y-m-d g:i A', strtotime($this->event_end_date));
	}

}

/* End of file syncedevent.php */
/* Location: ./application/models/syncedevent.php */
