function isNumberKey(evt) {
		var charCode = (evt.which) ? evt.which : evt.keyCode
	  	if (charCode > 31 && (charCode < 48 || charCode > 57)){
	  		alert("Please enter valid number");
	  		return false;
	  	}
	  	return true;
	}

	$(document).ready(function () {

	    $("#lorry-form").validate({
	    	ignore: [],
	        rules: {
	            "material_id": {
	                required: true,
	            },
	            "supply_score": {
	                required: true,
	            },
	            "delivery_chellan_number": {
	                required: true,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "quantity": {
	                required: true,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	            "unit": {
	                required: true,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	        },
	        messages: {
	            "material_id": {
	                required: "Please select the material",
	            }, 
	            "supply_score": {
	                required: "Please select the supply score",
	            },
	            "delivery_chellan_number": {
	                required: "Please enter the delivery chellan number",
	            },
	            "quantity": {
	                required: "Please enter the quantity",
	            },
	            "unit": {
	                required: "Please select the unit",
	            },
	        },
	        submitHandler: function (form) {
	           form.submit();
	        }
	    });

	});