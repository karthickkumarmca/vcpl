<script>
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	
	function gethallmarkValidationRules(rule) {
		return validation = {
			"admin" : {
				"user_name" : {
					"required": {
						"message": "Please enter the User name"
					},
				},
				"password" : {
					"required": {
						"message": "Please enter the password name"
					},
				},
				"confirm_password" : {
					"required": {
						"message": "Please enter the confirm password name"
					},
				},
				"full_name" : {
					"required": {
						"message": "Please enter the full name"
					},
				},
				"email" : {
					"required": {
						"message": "Please enter the email name"
					},
				},
				"user_group_name" : {
					"required": {
						"message": "Please select the user group name"
					},
				},
				"site_name" : {
					"required": {
						"message": "Please select the site name"
					},
				},
				"phone_number" : {
					"required": {
						"message": "Please enter the mobile number"
					},
				},
				"role_name" : {
					"required": {
						"message": "Please select the role name"
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
		var validation 		= gethallmarkValidationRules(rule);
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
	$('#staff-details-submit').click(function(event){
		event.preventDefault();	
		var form         = "admin-form";
		var rule         = fieldRule.admin;
		var validation   = gethallmarkValidationRules(rule);
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
	$('#user_group_name').change(function(){
		/*let id = $(this).val();
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
		$.ajax({
            data:"id="+id,
            type:"post",
            url:"{{url('/')}}/master/product-details/get-sub-category",
            success:function(html){
	            if(html!=''){
	               $('#site_name').html(html);
	           	}
         	}
       });*/
	})
	
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
