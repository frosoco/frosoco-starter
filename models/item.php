<?

class Item extends DataMapper {
	
	var $has_one = array("user");

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

}

/* End of file group.php */
/* Location: ./application/models/group.php */
