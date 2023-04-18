/**
 - formValidation.js
 - Validate Form Inputs
 - Project: Texaco Admin Portal
 **/

 var formValidation = {
    doFormValidation: function(request, validator) {
        var response = {
            valid: true,
            errorMessages: {}
        };
        for (i in request) {
            var inp_index = 0;
            var contain_array = false;
            var input_name = request[i]["name"];
            var input_value = request[i]["value"];
            var inputArray = input_name.match(/(.*?)\[(.*?)\]/);

            if (inputArray != null) {
                var vr_key = inputArray[1];
                inp_index = inputArray[2];
                contain_array = true;
            } else {
                vr_key = input_name;
                contain_array = false;
            }

            if (vr_key in validator) {
                var conditions = validator[vr_key];
                for (attr in conditions) {
                    var condition = conditions[attr];
                    var threshold = condition["value"];
                    var error = "";

                    var existing_error =
                    vr_key in response.errorMessages
                    ? response.errorMessages[vr_key]
                    : "";
                    var existing_errorMsg = contain_array
                    ? existing_error.hasOwnProperty(inp_index)
                    ? existing_error[inp_index]
                    : ""
                    : existing_error;
                    if (existing_errorMsg.length == 0) {
                        switch (attr) {
                            case "required":
                            if (threshold) {
                                if (input_value.length == 0) {
                                    error = condition["message"];
                                    response.valid = false;
                                }
                            }
                            break;
                            case "min":
                            if (input_value.length > 0) {
                                if (conditions.integer) {
                                    if (
                                        parseInt(input_value) <
                                        parseInt(threshold)
                                        ) {
                                        error = condition["message"];
                                    response.valid = false;
                                }
                            } else {
                                if (input_value.length < threshold) {
                                    error = condition["message"];
                                    response.valid = false;
                                }
                            }
                        }
                        break;
                        case "max":
                        if (input_value.length > threshold) {
                            error = condition["message"];
                            response.valid = false;
                        }
                        break;
                        case "characters":
                            if (input_value.length > 0) {
                                var regex = /^([a-z A-Z])+$/;
                                if (!regex.test(input_value)) {
                                    error = condition["message"];
                                    response.valid = false;
                                }
                            }
                            break;
                        case "integer":
                        if (threshold) {
                            if (
                                !Number.isInteger(Number(input_value))
                                ) {
                                error = condition["message"];
                            response.valid = false;
                        }
                    }
                    break;
                    case "number":
                    if (threshold && input_value.length) {
                        if (
                            isNaN(parseFloat(input_value)) ||
                            !$.isNumeric(input_value)
                            ) {
                            error = condition["message"];
                        response.valid = false;
                    }
                }
                break;
                            case "gt": // Greater than
                            if (Number(input_value) < threshold) {
                                error = condition["message"];
                                response.valid = false;
                            }
                            break;
                            case "gte": // Greater than or equal
                            if (Number(input_value) <= threshold) {
                                error = condition["message"];
                                response.valid = false;
                            }
                            break;
                            case "lt": // Less than
                            if (Number(input_value) > threshold) {
                                error = condition["message"];
                                response.valid = false;
                            }
                            break;
                            case "email":
                            if (input_value.length > 0) {
                                var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                if (!regex.test(input_value)) {
                                    error = condition["message"];
                                    response.valid = false;
                                }
                            }
                            break;
                            case "confirm_password":
                            if (input_value.length > 0) {
                                if (input_value != threshold) {
                                    error = condition["message"];
                                    response.valid = false;
                                }
                            }
                            break;
                            case "date":
                            if(input_value.length > 0) {
                                if(!moment(input_value, threshold,true).isValid()) {
                                    error          = condition['message'];
                                    response.valid = false;
                                }
                            }
                            break;
                        }
                        if (contain_array) {
                            if (response.errorMessages[vr_key]) {
                                var existingInfo =
                                response.errorMessages[vr_key];
                                existingInfo[inp_index] = error;

                                response.errorMessages[vr_key] = existingInfo;
                            } else {
                                var errorInfo = [];
                                errorInfo[inp_index] = error;
                                response.errorMessages[vr_key] = errorInfo;
                            }
                        } else {
                            response.errorMessages[vr_key] = error;
                        }
                    }
                }
            }
        }

        return response;
    },

    renderFormErrorMessages: function(form_id, errorMessages) {
        var initialInputName = "";
        for (name in errorMessages) {
            var message = errorMessages[name];
            if ($.isArray(message) || typeof message === "object") {
                for (m in message) {
                    var input_name = name + "[" + m + "]";
                    $("#" + form_id + ' [name="' + input_name + '"]')
                    .next()
                    .html(message[m]);
                    if (message[m].length > 0) {
                        $(
                            "#" + form_id + ' [name="' + input_name + '"]'
                            ).addClass("error_border");
                        if(initialInputName == '') {
                            initialInputName = input_name;
                        }
                    }
                }
            } else {
                let errorField = $("#" + form_id + ' [name="' + name + '"]').siblings(".validation_error");
                if (errorField.length) {
                    errorField[0].innerHTML = message;
                    if (message.length > 0) {
                        $("#" + form_id + ' [name="' + name + '"]').addClass(
                            "error_border"
                            );
                        if(initialInputName == '') {
                            initialInputName = name;
                        }
                    }
                }
            }
        }
        if(initialInputName != "") {
            $('#'+form_id+' [name="'+initialInputName+'"]').focus();
        }
    },

    clearFormInputs: function(form_id, formInputs) {
        for (i in formInputs) {
            var name = formInputs[i]["name"];
            if (
                $("#" + form_id + ' [name="' + name + '"]')
                .next()
                .hasClass("validation_error")
                ) {
                $("#" + form_id + ' [name="' + name + '"]').removeClass(
                    "error_border"
                    );
            $("#" + form_id + ' [name="' + name + '"]')
            .next()
            .html("");
        }
    }
},

doValidate: function(input_value, validator) {
    var error_message = "";
    for (attr in validator) {
        var condition = validator[attr];
        var threshold = condition["value"];
        if (error_message.length == 0) {
            switch (attr) {
                case "required":
                if (threshold) {
                    if (input_value.length == 0) {
                        error_message = condition["message"];
                    }
                }
                break;
                case "min":
                if (input_value.length > 0) {
                    if (validator.integer) {
                        if (input_value < threshold) {
                            error_message = condition["message"];
                        }
                    } else {
                        if (input_value.length < threshold) {
                            error_message = condition["message"];
                        }
                    }
                }
                break;
                case "max":
                if (input_value.length > threshold) {
                    error_message = condition["message"];
                }
                break;
                case "integer":
                if (threshold) {
                    if (!Number.isInteger(Number(input_value))) {
                        error_message = condition["message"];
                    }
                }
                break;
                case "number":
                if (threshold && input_value.length) {
                    if (
                        isNaN(parseFloat(input_value)) ||
                        !$.isNumeric(input_value)
                        ) {
                        error_message = condition["message"];
                }
            }
            break;
                    case "gt": // Greater than
                    if (Number(input_value) < threshold) {
                        error_message = condition["message"];
                    }
                    break;
                    case "gte": // Greater than or equal
                    if (Number(input_value) <= threshold) {
                        error_message = condition["message"];
                    }
                    break;
                    case "lt": // Less than
                    if (Number(input_value) > threshold) {
                        error_message = condition["message"];
                    }
                    break;
                    case "email":
                    if (input_value.length > 0) {
                        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                        if (!regex.test(input_value)) {
                            error_message = condition["message"];
                        }
                    }
                    break;
                    case "characters":
                    if (input_value.length > 0) {
                        var regex = /^([a-z A-Z])+$/;
                        if (!regex.test(input_value)) {
                            error_message = condition["message"];
                        }
                    }
                    break;
                    case "confirm_password":
                    if (input_value.length > 0) {
                        if (input_value != threshold) {
                            error_message = condition["message"];
                        }
                    }
                    break;
                    case "date":
                    if(input_value.length > 0) {
                        if(!moment(input_value, threshold,true).isValid()) {
                            error_message = condition['message'];
                        }
                    }
                    break;
                }
            }
        }
        return error_message;
    }
};

$(document).on("click", ".error_border", function() {
    $(this).removeClass("error_border");
    if (
        $(this)
        .next()
        .hasClass("validation_error")
        ) {
        $(this)
    .next()
    .html("");
}
});
