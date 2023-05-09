<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	$('#category_id').change(function(){
		let id = $(this).val();

		$.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : "{{ url('/') }}/master/product-rental/get-product-list",
        data : {'id' : id},
        type : 'POST',
        success: function(data){
            $("#product_details_id").html(data);
        }
    });
	})
	$("#admin-form").validate({
		rules: {
			category_id: "required",
			product_details_id: "required",
			rent_unit: {
	            required: true,
	            normalizer:function( value ) {
	           		return $.trim(value);
	            },
	        }
			
		},
		messages: {
			category_id: "Please select category name",
			product_details_id: "Please select sub category name",
			rent_unit: "Please enter product Rent",
		},
		submitHandler: function(form) {
			// console.log(form);
			form.submit();
		}
	});

</script>
