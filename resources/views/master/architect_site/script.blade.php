<script>
	$(document).ready(function () {

	    $("#admin-form").validate({
	    	ignore: [],
	        rules: {
	            "architect_name": {
	                required: true,
	                minlength: 1,
	                maxlength: 100,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "site_id": {
	                required: true,
	            },
	            "cader": {
	                required: true,
	                minlength: 1,
	                maxlength: 100,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "mobile_number": {
	                required: true,
	                minlength: 1,
	                maxlength: 10,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "email_id": {
	            	email:true,
	                required: true,
	                minlength: 1,
	                maxlength: 100,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "address": {
	                required: true,
	                minlength: 1,
	                maxlength: 100,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	        },
	        messages: {
	            "architect_name": {
	                required: "Please enter a architect name",
	                minlength: "Enter architect name minimum 1 character",
	                maxlength: "Enter architect name maxmimum 100 character",
	            },
	            "site_id": {
	                required: "Please enter a site name",
	            },
	            "cader": {
	                required: "Please enter a cader name",
	                minlength: "Enter cader name minimum 1 character",
	                maxlength: "Enter cader name maxmimum 100 character",
	            },
	            "mobile_number": {
	                required: "Please enter a mobile number",
	                minlength: "Enter client name minimum 1 character",
	                maxlength: "Enter client name maxmimum 10 character",
	            },
	            "email_id": {
	                required: "Please enter a email id",
	                minlength: "Enter client name minimum 1 character",
	                maxlength: "Enter client name maxmimum 100 character",
	            },
	            "address": {
	                required: "Please enter a address",
	                minlength: "Enter address minimum 1 character",
	                maxlength: "Enter address maxmimum 100 character",
	            },
	        },
	        submitHandler: function (form) {
	           form.submit();
	        }
	    });

	});

	
</script>
