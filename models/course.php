<?

class Course extends DataMapper {
	
	$has_many = array(
		'user' => array(
			'class' => 'user',
			'other_field' => 'course',
			'join_self_as' => 'course',
			'join_other_as' => 'user',
			'join_table' => 'users_courses'
			)
		);

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}
		
}

/* End of file course.php */
/* Location: ./application/models/course.php */
