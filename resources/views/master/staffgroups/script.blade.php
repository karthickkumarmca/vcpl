<script>

	$(document).ready(function () {

	    $("#admin-form").validate({
	    	ignore: [],
	        rules: {
	            "group_name": {
	                required: true,
	                minlength: 1,
	                maxlength: 100,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	        },
	        messages: {
	            "group_name": {
	                required: "Please enter a group name",
	                minlength: "Enter group name minimum 1 character",
	                maxlength: "Enter group name maximum 100 character",
	            },
	        },
	        submitHandler: function (form) {
	           form.submit();
	        }
	    });

	});

</script>
