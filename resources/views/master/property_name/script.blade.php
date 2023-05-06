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
			"property_name": {
                required: true,
                normalizer:function( value ) {
               		return $.trim(value);
                },
            },
			ownership_id: "required",
			
		},
		messages: {
			unit_name: "Please select the category name",
			property_name:"Please enter Property name",
			ownership_id: "Please select the Owner name",
		}
	});

</script>
