<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	
	$("#admin-form").validate({
		rules: {
			name: {
	            required: true,
	            normalizer:function( value ) {
	           		return $.trim(value);
	            },
	        },
	        description: {
	            required: true,
	            normalizer:function( value ) {
	           		return $.trim(value);
	            },
	        }
			
		},
		messages: {
			name: "Please enter Name",
			description: "Please enter description",
		},
		submitHandler: function(form) {
			// console.log(form);
			form.submit();
		}
	});

</script>
