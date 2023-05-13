<script>
	$("#admin-form").validate({
		rules: {
			"vehicle_name": {
                required: true,
                normalizer:function( value ) {
               		return $.trim(value);
                },
            },
            "vehicle_no": {
                required: true,
                normalizer:function( value ) {
               		return $.trim(value);
                },
            },
            "insurance_date":{
            	required: true,
            }
			
		},
		messages: {
			vehicle_name: "Please enter Vehicle name",
		},
		
	});

</script>
