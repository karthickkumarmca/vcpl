<?php

return [
    'httpSuccessCode' => 200,
    'httpFailureCode' => 400,
    'httpAuthInvalidCode' => 401,
    'httpCreatedCode' => 201,
    'user_type' => [
        1 => 'Super Admin',
        2 => 'Portal Users',
    ],
    
    "otp_expiry_time" => "PT600S",
    'email_type' => [
        "OTP" => 1,
    ],
    'device_type' => [
        'ANDROID' => 1,
        'IOS' => 2,
    ],
    'SEND_CONTACTUS_SUBJECT' => 'VCPL - Contact us Notifications',
    'SEND_FORGOT_PASSWORD_OTP' => 'VCPL - Forgot Password OTP',
    'SEND_RESET_PASSWORD' => 'VCPL - Reset Password',

    'SEND_USER_LOGIN' => 'VCPL - User Logged In',
    'SEND_USER_SIGNUP' => 'VCPL - User Registration',

    'MAP_KEY' => '',

    'error_maintainers' => [
        'dhineshmca.11@gmail.com'
    ],

    'SEND_OTP_SUBJECT' => 'VCPL - Confirm Your Identity',

    "site_captcha_key" => env('NOCAPTCHA_SITEKEY'),
];
