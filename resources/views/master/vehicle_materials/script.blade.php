<script>
	$("#admin-form").validate({
		rules: {
			"vehicle_name": {
                required: true,
                normalizer:function( value ) {
               		return $.trim(value);
                },
            },
			
		},
		messages: {
			vehicle_name: "Please enter Vehicle name",
		},
		
	});

</script>
