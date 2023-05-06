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
			subcategory_id: "required",
			product_name: "required",
			
		},
		messages: {
			category_id: "Please select category name",
			subcategory_id: "Please select sub category name",
			product_name: "Please enter product name",
		},
		submitHandler: function(form) {
			// console.log(form);
			form.submit();
		}
	});

</script>
