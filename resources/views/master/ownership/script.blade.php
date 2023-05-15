<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	
	$("#admin-form").validate({
		rules: {
			"position": {
                required: true,
                normalizer:function( value ) {
               		return $.trim(value);
                },
            },
			staff_id: "required",
			
		},
		messages: {
			ownership_name: "Please enter Owner ship name",
			position: "Please enter Position name",
			staff_id: "Please Select Staff name",
		}
	});

</script>
