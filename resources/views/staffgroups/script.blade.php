<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	
	function getrolesValidationRules(rule) {
		return validation = {
			"admin" : {
				"group_name" : {
					"required": {
						"message": "Please enter the staff group name"
					},
					
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
	*/
	$('#staff_groups_submit').click(function(event){
		event.preventDefault();	
		var form         = "admin-form";
		var rule         = fieldRule.admin;
		var validation   = getrolesValidationRules(rule);
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
