<div class="panel view-content">
	<div class="view-content-title">Courses</div>
	<input class="autocomplete" type="text" name="q" id="query" />
	<div class="user-courses">

	</div>
</div>
<div class="panel view-content">
	Things will update here on clicking things in the left
</div>
<script>
	$('#query').autocomplete({
		serviceUrl: '/courses/search', 
		paramName: 'q',
		onSelect: function(suggestion) {
			$.get('/courses/retrieve_data', { id: suggestion.data}).done(function(data) {
				$('#query').val('');
				$('.user-courses').append('<div class="user-course"><div class="user-course-number">' + data['number'] + '</div><div class="user-course-title">' + data['title'] + '</div></div>');
			});
		}
	});
</script>