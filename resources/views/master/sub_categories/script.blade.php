<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	
	$("#admin-form").validate({
		rules: {
			category_id: "required",
			sub_category_name: "required",
			
		},
		messages: {
			sub_category_name: "Please enter Sub Category name",
			category_id: "Please enter Role name",
		},
		submitHandler: function(form) {
			// console.log(form);
			form.submit();
		}
	});

</script>
