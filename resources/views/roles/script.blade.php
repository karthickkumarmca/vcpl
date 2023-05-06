<script>
	$(document).ready(function () {

	    $("#admin-form").validate({
	        rules: {
	            "role_name": {
	                required: true,
	                minlength: 1,
	            },
	        },
	        messages: {
	            "role_name": {
	                required: "Please enter a role name",
	                minlength: "Enter role name minimum 1 character"
	            },
	        },
	        submitHandler: function (form) {
	            alert('valid form submitted'); 
	            return false; // for demo
	        }
	    });

	});
	
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*
	
	function getrolesValidationRules(rule) {
		return validation = {
			"admin" : {
				"role_name" : {
					"required": {
						"message": "Please enter the Role name"
					},
					
				},
            },
        };
    }

	/**
	* Validate the input whenever the input value is changed
	*
	$(document).on('blur', '.pos_validate', function() {
		var rule_type 		= $(this).data('rule');
		var rule 			= fieldRule[rule_type];
		var validation 		= getrolesValidationRules(rule);
		var input_name 		= $(this).attr('name');
		var name 			= "";
		var inputArray 		= input_name.match(/(.*?)\[(.*?)\]/);
		if(inputArray != null) {
			name = inputArray[1];
		}else {
			name = input_name;
		}

		var validator 		= validation[rule_type][name];
		var input_value 	= $(this).val();
		var error_message 	= formValidation.doValidate(input_value, validator);

		if($(this).next().hasClass('validation_error')) {
			if(error_message.length == 0) {
				$(this).next().html(error_message);
				$(this).removeClass('error_border');
			} else {
				$(this).next().html(error_message);
				$(this).addClass('error_border');
			}
		}
	});

	/**
	* Create/Update the hallmark form
	*
	$('#Roles-submit').click(function(event){
		event.preventDefault();	
		var form         = "admin-form";
		var rule         = fieldRule.admin;
		var validation   = getrolesValidationRules(rule);
		var data         = $('#'+form).serializeArray();
		var validator    = validation.admin;
		formValidation.clearFormInputs(form, data);
		var formResponse = formValidation.doFormValidation(data, validator);
		if (formResponse.valid) {
	$("#admin-form").validate({
		rules: {
			role_name: "required",
			master: "required",
			
		},
		messages: {
			role_name: "Please enter Role name",
		},
		submitHandler: function(form) {
			// console.log(form);
			form.submit();
		}
	});
	

</script>
