@include('mails.header')
<tr>
  <td valign="top" style="line-height: 0px;">
    <h1 style="text-align: center; margin:50px auto; color: #68BA58; font-weight: 700; font-size:2.8rem; font-family: 'Roboto', sans-serif;">Confirm Your Identity.</h1>
  </td>   
</tr>
<tr>
  <td> 
    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="font-size: 18px; line-height: 26px; color: #707070; font-weight: 500; margin-bottom: 20px;">
      <tbody>
        <tr>
          <td style="text-align: center">
            <img src="{{$message->embed(public_path('images/email_template/confirm-identity.png'))}}" alt="confirm-identity">
          </td>
        </tr>
        <tr>
          <td style="text-align: center"> 
            Your One Time Password (OTP) has been generated.
          </td>
        </tr>

        <tr>
          <td valign="top" style="line-height: 0px;">
            <h1 style="text-align: center; margin:50px auto; color: #68BA58; font-weight: 700; font-size:2.8rem; font-family: 'Roboto', sans-serif;">{{ $data['otp'] }}</h1>
          </td>
        </tr>

        <tr>
          <td style="text-align: center"> 
            @if($data['type'] == 'login')
            Please use this OTP to complete your log in process. 
            @elseif($data['type'] == 'register')
            Please use this OTP to complete your registration. <br/>This OTP will expire in 10 minutes. If this attempt was unsuccessful, please request for a new OTP from the <a href="{{$data['return_url']}}">site</a>.
            @elseif($data['type'] == 'infoupdate')
            Please use this OTP to complete your changes on your profile. 
            @endif
          </td>
        </tr>
        <tr>
          <td style="text-align: center"> 
            &nbsp;
          </td>
        </tr>
      </tbody>
    </table>

  </td>
</tr>
@include('mails.footer')