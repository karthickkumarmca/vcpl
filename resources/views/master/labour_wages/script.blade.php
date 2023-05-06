<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	
	$("#admin-form").validate({
		rules: {
			site_id: "required",
			sub_contractor_id: "required",
			labour_category_id: "required",
			rate: "required",
			
		},
		messages: {
			site_id: "Please select Site name",
			sub_contractor_id: "Please select Sub contractor name",
			labour_category_id: "Please select Labour name",
			rate: "Please enter rate",
		},
		submitHandler: function(form) {
			// console.log(form);
			form.submit();
		}
	});

</script>
