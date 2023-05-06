<script>

	$(document).ready(function () {

	    $("#admin-form").validate({
	    	ignore: [],
	        rules: {
	            "role_name": {
	                required: true,
	                minlength: 1,
	                maxlength: 100,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "master[]": {
	                required: true,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	        },
	        messages: {
	            "role_name": {
	                required: "Please enter a role name",
	                minlength: "Enter role name minimum 1 character",
	                maxlength: "Enter role name minimum 1 character",
	            },
	            "master[]": {
	                required: "Please select the master modules",
	            },
	        },
	        submitHandler: function (form) {
	           form.submit();
	        }
	    });

	});
</script>
