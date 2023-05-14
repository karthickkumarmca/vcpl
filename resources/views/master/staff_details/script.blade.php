<script>
	$(document).ready(function () {

	    $("#admin-form").validate({
	    	ignore: [],
	        rules: {
	            "name": {
	                required: true,
	                minlength: 1,
	                maxlength: 100,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "user_name": {
	                required: true,
	                minlength: 5,
	                maxlength: 10,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "password": {
	                required: true,
	                minlength: 1,
	                maxlength: 100,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "phone_number": {
	                required: true,
	                minlength: 1,
	                maxlength: 10,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	             "user_groups_id": {
	                required: true,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "role_id": {
	                required: true,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	        },
	        messages: {
	            "name": {
	                required: "Please enter name",
	                minlength: "Enter name minimum 1 character",
	                maxlength: "Enter name maxmimum 100 character",
	            },
	            "user_name": {
	                required: "Please enter employee code",
	                minlength: "Enter user employee code minimum 5 character",
	                maxlength: "Enter user employee code maxmimum 10 character",
	            }, 
	            "password": {
	                required: "Please enter password",
	                minlength: "Enter password minimum 1 character",
	                maxlength: "Enter password maxmimum 100 character",
	            },
	            "email": {
	                required: "Please enter email",
	                minlength: "Enter password minimum 1 character",
	                maxlength: "Enter password maxmimum 100 character",
	            },
				"phone_number": {
	                required: "Please enter password",
	                minlength: "Enter password minimum 1 character",
	                maxlength: "Enter password maxmimum 10 character",
	            },
	            "user_groups_id": {
	                required: "Please select user group ",
	            },
	            "role_id": {
	                required: "Please select role name",
	            },
	        },
	        submitHandler: function (form) {
	           form.submit();
	        }
	    });

	});

</script>
