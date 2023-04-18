<script>
	$(document).on('change','#user_image',function(){
		//alert(this.files[0].size);
		var property = document.getElementById('user_image').files[0];
		var image_name = property.name;
		var image_extension = image_name.split('.').pop().toLowerCase();
		if (this.files[0].size > 5000000){
			toastr.error("Allowed file size exceeded. (Max. 5 MB)");
			this.value = null;
			return false;
		}
		if(jQuery.inArray(image_extension,['png','gif','jpg','jpeg']) == -1){
			toastr.error('Uploaded file is not a valid format.Please upload the JPG,PNG format files.');
			return false;
		}
       // $('#customFileLabel').html('File Selected');
   });
	/**
	* Define Admin Form validation rules and messages
	*
	* @param object rule
	* @return object validation
	*/
	function getUserValidationRules(rule) {
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
                "password" : {
                	"required": {
                		"value": true,
                		"message": "Please enter the password"
                	},
                	"max": {
                		"value": rule.password.max,
                		"message": "Maximum length is "+rule.password.max
                	}
                },
                "confirm_password" : {
                	"required": {
                		"value": true,
                		"message": "Please enter the confirm password"
                	},
                	"max": {
                		"value": rule.password.max,
                		"message": "Maximum length is "+rule.password.max
                	},
                	"confirm_password": {
                		"value": $('#user-password').val(),
                		"message": "Password does not match"
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
		var validation 		= getUserValidationRules(rule);
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
	* Create/Update the user form
	*/
	$('#user-submit').click(function(event){
		event.preventDefault();	
		var form         = "admin-form";
		var rule         = fieldRule.admin;
		var validation   = getUserValidationRules(rule);
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


	$("#profile-form").validate({
		rules: {
			facebook: {
				required: true,
			},
			twitter: {
				required: true,
			},
			instagram: {
				required: true,
			},
		},
		messages: {
			facebook: {
				required: "Please Enter Facebook url",
			},
			twitter: {
				required: "Please Enter Twitter url",
			},
			instagram: {
				required: "Please Enter Instagram url",
			},
		},
		submitHandler: function (form) {
			let facebook         = $('#facebook').val();
			let valid_fb=isurlcheck(facebook);
			if(!valid_fb)
			{
				toastr.error('Please Enter valid Facebook url', 'Error !');
				return false;
			}
			let twitter         = $('#twitter').val();
			let valid_twit=isurlcheck(twitter);
			if(!valid_twit)
			{
				toastr.error('Please Enter valid Twitter url', 'Error !');
				return false;
			}
			let instagram         = $('#instagram').val();
			let valid_insta=isurlcheck(instagram);
			if(!valid_insta)
			{
				toastr.error('Please Enter valid Instagram url', 'Error !');
				return false;
			}
			if(valid_fb && valid_twit && valid_insta)
			{
				$(".corona-preloader-backdrop").show();
				form.submit();
			}
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

	$("#reward-points-form").validate({
		rules: {
			amount_to_reward: {
				min:0.01,
				required: true,
			},
			reward_to_amount: {
				min:0.01,
				required: true,
			},
		},
		messages: {
			amount_to_reward: {
				required: "Please Enter Amount To Reward Points",
			},
			reward_to_amount: {
				required: "Please Enter Reward Points To Amount",
			},
		},
		submitHandler: function (form) {
			$(".corona-preloader-backdrop").show();
			form.submit();
		}
	});

</script>
