<?

class Upload extends DataMapper {

	var $has_one = array("user");
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

}

/* End of file upload.php */
/* Location: ./application/models/upload.php */
