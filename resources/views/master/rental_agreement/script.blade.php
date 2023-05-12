<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	$('#property_id').change(function(){
		let id = $(this).val();

		$.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : "{{ url('/') }}/master/rental-agreement/getname",
        data : {'id' : id},
        type : 'POST',
        success: function(data){
            $("#category_name").val(data.category_name);
            $("#ownership_name").val(data.ownership_name);
        }
    });
	})
	$("#admin-form").validate({
		rules: {
			tenant_name: {
	            required: true,
	            normalizer:function( value ) {
	           		return $.trim(value);
	            },
	        },
	        aadhar_number: {
	            required: true,
	            normalizer:function( value ) {
	           		return $.trim(value);
	            },
	        },
	        rent_start_date: {
	            required: true,
	        },
	        property_id: {
	            required: true,
	        },
	        rent_end_date: {
	            required: true,
	        },
	        rental_area: {
	            required: true,
	        },
	        rental_amount: {
	            required: true,
	        },
			maintainance_charge: {
	            required: true,
	        },
	        next_increment: {
	            required: true,
	        },
	        present_rental_rate: {
	            required: true,
	        },
	        advance_paid: {
	            required: true,
	        },
	        contact_person_name: {
	            required: true,
	            normalizer:function( value ) {
	           		return $.trim(value);
	            },
	        },
	        contact_person_mobile_number: {
	            required: true,
	            normalizer:function( value ) {
	           		return $.trim(value);
	            },
	        },
			
		},
		submitHandler: function(form) {
			// console.log(form);
			form.submit();
		}
	});

</script>
