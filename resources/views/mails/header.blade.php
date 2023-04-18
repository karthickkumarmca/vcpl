<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email News</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0px;
            padding: 0px;   
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
        }
    </style>
</head> 

<body style="background:#dcdcdc;margin-top:20px;margin-bottom: 20px">
    <table width="700" border="0" align="center" cellpadding="0" cellspacing="0" style="border: solid 1px #e4e4e4; background: #fff; font-family: 'Roboto', sans-serif;">
        <tbody>
            <tr>
                <td style="line-height: 0px;">
                    <img src="{{$message->embed(public_path('images/email_template/logo.png'))}}" alt=" logo">
                </td>
            </tr>