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
			rate_unit: "required",
			units_id: "required",
			from_date: "required",
			to_date: "required",
			
		},
		messages: {
			category_id: "Please select the Materal name",
			rate_unit: "Please enter Unit/rate name",
			units_id: "Please select the Unit name",
			from_date: "Please select From Date",
			to_date: "Please select To Date",
		},
		
	});

</script>
