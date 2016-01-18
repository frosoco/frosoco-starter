<?

class Quote extends DataMapper {

	var $has_one = array("user");
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

}

/* End of file post.php */
/* Location: ./application/models/post.php */
