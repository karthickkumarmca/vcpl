<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	
	$("#admin-form").validate({
		rules: {
			role_name: "required",
			master: "required",
			
		},
		messages: {
			role_name: "Please enter Role name",
		},
		submitHandler: function(form) {
			// console.log(form);
			form.submit();
		}
	});
	

</script>
