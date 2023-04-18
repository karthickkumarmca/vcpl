<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	function getcustomerValidationRules(rule) {
		return validation = {
			"admin" : {
				"name" : {
					"required": {
						"value": true,
						"message": "Please enter the name"
					},
					"max": {
						"value": rule.name.max,
						"message": "Maximum length is "+rule.name.max
					},
					"min": {
						"value": rule.name.min,
						"message": "Minimum length is "+rule.name.min
					}
				},
				"email" : {
					"required": {
						"value": true,
						"message": "Please enter the email"
					},
					"email": {
						"value": true,
						"message": "Please enter the valid email address"
					},
					"max": {
						"value": rule.email.max,
						"message": "Maximum length is "+rule.email.max
					}
				},
				"phone" : {
					"required": {
						"value": true,
						"message": "Please enter the phone number"
					},
					"min": {
						"value": rule.phone.min,
						"message": "Minimum length is "+rule.phone.min
					},
					"number": {
						"value": true,
						"message": "Phone Number should contain only numbers"
					}
				},
            },
        };
    }

	/**
	* Validate the input whenever the input value is changed
	*/
	$(document).on('blur', '.pos_validate', function() {
		var rule_type 		= $(this).data('rule');
		var rule 			= fieldRule[rule_type];
		var validation 		= getcustomerValidationRules(rule);
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
	* Create/Update the customer form
	*/
	$('#customer-submit').click(function(event){
		event.preventDefault();	
		var form         = "admin-form";
		var rule         = fieldRule.admin;
		var validation   = getcustomerValidationRules(rule);
		var data         = $('#'+form).serializeArray();
		var validator    = validation.admin;
		formValidation.clearFormInputs(form, data);
		var formResponse = formValidation.doFormValidation(data, validator);
		if (formResponse.valid) {
			if (path.indexOf('localhost') != -1) {
			}
			else{
				if (grecaptcha.getResponse()) {
					

				} else {
					toastr.error('Please confirm captcha to proceed');
					return false;
				}
			}
			$(".corona-preloader-backdrop").show();
			$( "#admin-form" ).submit();
		}
		else {
			formValidation.renderFormErrorMessages(form, formResponse.errorMessages);
			return false;
		}
	});
	function isurlcheck(str)
	{
		regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
		if (regexp.test(str))
		{
			if(Number.isInteger(str))
			{
				return false;
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode != 46 && charCode > 31 
			&& (charCode < 48 || charCode > 57))
			return false;

		return true;
	}

</script>
