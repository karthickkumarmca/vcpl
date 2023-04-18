<!DOCTYPE html>
<html>

<head>
    <title>ENDS EMAIL</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="max-width:720px;margin: auto;">
    <table style="background: #fff;width:600px;border-bottom: 6px solid #fbdb0f;">
        <tr>
            <td colspan="2" style="text-align:left;width:50%;">
                <!-- <img src="{{ $message->embed(asset('images/logo.png')) }}" alt="ends-logo" style="max-width:40%; margin: 30px auto;display: block;"> -->
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0" style="padding:3%;background-repeat:no-repeat;background-size:contain;">
                    <tbody>
                        <tr>
                            <td valign="top" style="padding:10px;font:16px Arial,sans-serif;width:50%;color:#89898c" colspan="3">
                                Dear User
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px;font:14px Arial,sans-serif;font-weight:bold; color: #3d3d3d;" colspan="3">
                                As per your request, Your One Time Password (OTP) has been Generated.
                            </td>
                        </tr>
                        <tr style="background: #0c7a3d;">
                            <td style="padding:10px;font:18px Arial,sans-serif;font-weight:bold;text-align: center; color:#fff;width:50%; border-radius:5px;" colspan="3">
                                {{$otp}}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px;font:14px Arial,sans-serif;font-weight:bold;color: #3d3d3d;" colspan="3">
                                Please use this OTP to complete your registration
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:5px 10px;font:16px Arial,sans-serif;color:#89898c" colspan="3">Note:</td>
                        </tr>
                        <tr>
                            <td style="padding:5px 10px;font:14px Arial,sans-serif;font-weight:bold;color: #3d3d3d;" colspan="3">
                                This OTP will expire after a 30sec, even if the registeration was not sucessful. if this attempt was unsucessful, and you wish to retry it, a new OTP will be generated to complete it.
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:30px 10px 5px;font:14px Arial,sans-serif;font-weight:bold;text-align: center;color: #3d3d3d;" colspan="3">
                                *** This is an auto-generated email. Please do not replay to this email. ***
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h2 style="width: 100%;text-align: center;border-bottom: 1px solid #2ac054;line-height: 0.1em;margin: 10px 0 20px;font-family: Arial,sans-serif;font-size:18px;">
                    <span style="background:#fff;padding:0 10px;">Thank you for your Registration!</span>
                </h2>
                <footer style="padding:2px;background:#fbdb0f; text-align:center;width:100%;font-family: Arial,sans-serif;border-radius:5px">
                    <h4 style="color:#000000;font:12px Arial,sans-serif;">OTP is a secure code used to secure your access your
                        E-commerce National Delivery Solution.</h4>
                </footer>
            </td>
        </tr>
    </table>
</body>

</html>