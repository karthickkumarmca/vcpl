<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	
	$("#admin-form").validate({
		rules: {
			category_name: {
	            required: true,
	            normalizer:function( value ) {
	           		return $.trim(value);
	            },
	        }
			
		},
		messages: {
			category_name: "Please enter category name",
		},
		
	});

</script>
