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
			"rate_unit": {
                required: true,
                normalizer:function( value ) {
               		return $.trim(value);
                },
            },
			units_id: "required",
			
		},
		messages: {
			category_id: "Please select the Materal name",
			rate_unit: "Please enter Unit/rate name",
			units_id: "Please select the Unit name",
		},
		
	});

</script>
