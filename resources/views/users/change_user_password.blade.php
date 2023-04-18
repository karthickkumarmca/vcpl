@extends('layouts.main')
@section('content')
<section class="content-header">
    <h1 class="col-lg-6 no-padding">
        Change password
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url(route('home')) }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ url(route('user-list'))  }}">User management</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <form id="admin-form">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Change password</h3>
                    </div>

                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Current password <span class="text-danger"> *</span></label>
                                <input type="password" class="form-control pos_validate allow_characters" id="current_password" name="current_password" value="" data-rule="admin" autocomplete="new-password" />
                                <span class="validation_error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>New password <span class="text-danger"> *</span></label>
                                <input type="password" class="form-control pos_validate allow_characters" id="new_password" name="new_password" value="" data-rule="admin" autocomplete="new-password" />
                                <span class="validation_error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Confirm password <span class="text-danger"> *</span></label>
                                <input type="password" class="form-control pos_validate allow_characters" id="confirm_password" name="confirm_password" value="" data-rule="admin" autocomplete="new-password" />
                                <span class="validation_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group has-feedback @if ($errors->has('g-recaptcha-response')) has-error @endif col-md-12" id="recaptcha-boxs" style="max-width:100%;height: 0px !important;">
                                <div id="capcha-elements"></div>
                                <span class="validation_error"></span>
                                @if($errors->has('g-recaptcha-response'))
                                <div class="error">{{ $errors->first('g-recaptcha-response') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="button" id="user-submit" class="btn btn-success">
                                <strong>Save</strong>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<form id="user-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
</section>
@endsection
@section('after-scripts-end')
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script>
    var siteCaptchaKey = "{{config('general_settings.site_captcha_key')}}";
    var onloadCallback = function() {
        grecaptcha.render('capcha-elements', {
            'sitekey': siteCaptchaKey
        });
    };
    /**
     * Define Admin Form validation rules and messages
     *
     * @param object rule
     * @return object validation
     */
     function getUserValidationRules(rule)
     {
        return validation = {
            "admin":
            {
                "current_password":
                {
                    "required":
                    {
                        "value": true,
                        "message": "Please enter the current password"
                    },
                    "min":
                    {
                        "value": rule.password.min,
                        "message": "Maximum length is " + rule.password.min
                    },
                    "max":
                    {
                        "value": rule.password.max,
                        "message": "Maximum length is " + rule.password.max
                    }
                },
                "new_password":
                {
                    "required":
                    {
                        "value": true,
                        "message": "Please enter the new password"
                    },
                    "min":
                    {
                        "value": rule.password.min,
                        "message": "Maximum length is " + rule.password.min
                    },
                    "max":
                    {
                        "value": rule.password.max,
                        "message": "Maximum length is " + rule.password.max
                    }
                },
                "confirm_password":
                {
                    "required":
                    {
                        "value": true,
                        "message": "Please enter the confirm password"
                    },
                    "max":
                    {
                        "value": rule.password.max,
                        "message": "Maximum length is " + rule.password.max
                    },
                    "min":
                    {
                        "value": rule.password.min,
                        "message": "Maximum length is " + rule.password.min
                    },
                    "confirm_password":
                    {
                        "value": $('#new_password').val(),
                        "message": "Password does not match"
                    }
                },
            },
        };
    }

    /**
     * Validate the input whenever the input value is changed
     */
     $(document).on('blur', '.pos_validate', function()
     {
        var rule_type = $(this).data('rule');
        var rule = fieldRule[rule_type];
        var validation = getUserValidationRules(rule);
        var input_name = $(this).attr('name');
        var name = "";
        var inputArray = input_name.match(/(.*?)\[(.*?)\]/);
        if (inputArray != null)
        {
            name = inputArray[1];
        }
        else
        {
            name = input_name;
        }

        var validator = validation[rule_type][name];
        var input_value = $(this).val();
        var error_message = formValidation.doValidate(input_value, validator);

        if ($(this).next().hasClass('validation_error'))
        {
            if (error_message.length == 0)
            {
                $(this).next().html(error_message);
                $(this).removeClass('error_border');
            }
            else
            {
                $(this).next().html(error_message);
                $(this).addClass('error_border');
            }
        }
    });

    /**
     * Create/Update the user form
     */
     $('#user-submit').click(function()
     {
        var form = "admin-form";
        var rule = fieldRule.change_password;
        var validation = getUserValidationRules(rule);
        var data = $('#' + form).serializeArray();
        var validator = validation.admin;
        formValidation.clearFormInputs(form, data);
        var formResponse = formValidation.doFormValidation(data, validator);

        if (formResponse.valid)
        {
            if (path.indexOf('localhost') != -1) {
            }
            else{
                if (grecaptcha.getResponse()) {

                } else {
                    toastr.error('Please confirm captcha to proceed');
                    return false;
                }
            }
            var request = {
                'url': "{{url('change-user-password')}}",
                'type': "POST",
                'data': data,
                'dataType': "json",
                'success_message': true,
                'error_message': true,
                'window_reload': true,
                'ajax_loader': true
            };
            dataTable.makeRequest(request, processCurrentResponse);

            function processCurrentResponse(response, status_code)
            {
                if (response)
                {
                    if (status_code == appConfig.response_code.ok)
                    {
                        $("#new_password").val("");
                        $("#confirm_password").val("");
                        //window.location.href = "{{ route('logout') }}";
                        document.getElementById('user-logout-form').submit();
                        dataTable.showNotificationMessage(1, response.message);
                    }
                    else if (status_code == appConfig.response_code.bad_request)
                    {
                        var errorMessages = response.error ? response.error :
                        {};
                        formValidation.renderFormErrorMessages(form, errorMessages);
                    }
                }
            }
        }
        else
        {
            formValidation.renderFormErrorMessages(form, formResponse.errorMessages);
        }
    });
</script>

@stop