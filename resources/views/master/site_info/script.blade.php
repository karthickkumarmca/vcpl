<script>

	$(document).ready(function () {

	    $("#admin-form").validate({
	    	ignore: [],
	        rules: {
	            "site_name": {
	                required: true,
	                minlength: 1,
	                maxlength: 100,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "site_location": {
	                required: true,
	                minlength: 1,
	                maxlength: 100,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "site_engineer_id": {
	                required: true,
	            },
	            "sub_contractor_id": {
	                required: true,
	            },
	            "store_keeper_id": {
	                required: true,
	            },
	        },
	        messages: {
	            "site_name": {
	                required: "Please enter a site name",
	                minlength: "Enter role name minimum 1 character",
	                maxlength: "Enter role name maxmimum 100 character",
	            },
	            "site_location": {
	                required: "Please enter a site location",
	                minlength: "Enter site location minimum 1 character",
	                maxlength: "Enter site location maxmimum 100 character",
	            },
	            "site_engineer_id": {
	                required: "Please select site engineer",
	            },
	            "sub_contractor_id": {
	                required: "Please select sub contractor",
	            },
	            "store_keeper_id": {
	                required: "Please select store keeper",
	            },
	        },
	        submitHandler: function (form) {
	           form.submit();
	        }
	    });
	});

</script>
