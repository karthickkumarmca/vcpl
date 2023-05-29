function isNumberKey(evt) {
		var charCode = (evt.which) ? evt.which : evt.keyCode
	  	if (charCode > 31 && (charCode < 48 || charCode > 57)){
	  		alert("Please enter valid number");
	  		return false;
	  	}
	  	return true;
	}
	$(document).ready(function () {

	    $("#labour-form").validate({
	    	ignore: [],
	        rules: {
	            "subcontractor_id": {
	                required: true,
	                /*normalizer:function( value ) {
	               		return $.trim(value);
	                },*/
	            },
	            "shift_id": {
	                required: true,
	                /*normalizer:function( value ) {
	               		return $.trim(value);
	                },*/
	            },
	            "labour_date": {
	                required: true,
	                /*normalizer:function( value ) {
	               		return $.trim(value);
	                },*/
	            },
	        },
	        messages: {
	            "subcontractor_id": {
	                required: "Please select sub contractor id",
	            }, 
	            "shift_id": {
	                required: "Please shift type",
	            },
	            "labour_date": {
	                required: "Please select labour date",
	            },
	        },
	        submitHandler: function (form) {
	           form.submit();
	        }
	    });

	});