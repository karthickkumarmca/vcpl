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
        url : "{{ url('/') }}/master/product-details/get-sub-category",
        data : {'id' : id},
        type : 'POST',
        success: function(data){
            $("#subcategory_id").html(data);
        }
    });
	})
	$("#admin-form").validate({
		rules: {
			category_id: "required",
			subcategory_id: "required",
			product_name: {
	            required: true,
	            normalizer:function( value ) {
	           		return $.trim(value);
	            },
	        }
			
		},
		messages: {
			category_id: "Please select category name",
			subcategory_id: "Please select sub category name",
			product_name: "Please enter product name",
		},
		submitHandler: function(form) {
			// console.log(form);
			form.submit();
		}
	});

</script>
