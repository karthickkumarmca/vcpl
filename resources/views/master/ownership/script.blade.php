<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	
	$("#admin-form").validate({
		rules: {
			ownership_name: "required",
			short_name: "required",
			position: "required",
			email: "required",
			
		},
		messages: {
			ownership_name: "Please enter Owner ship name",
			short_name: "Please enter short name",
			position: "Please enter Position name",
			email: "Please enter Position name",
		}
	});

</script>
