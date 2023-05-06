<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	
	$("#admin-form").validate({
		rules: {
			category_name: "required",
			
		},
		messages: {
			category_name: "Please enter Role name",
		},
		submitHandler: function(form) {
			// console.log(form);
			form.submit();
		}
	});
</script>
