@include('mails.header')
<tr>
  <td valign="top" style="line-height: 0px;">
    <h1 style="text-align: center; margin:50px auto; color: #68BA58; font-weight: 700; font-size:2.8rem; font-family: 'Roboto', sans-serif;">New Password.</h1>
  </td>   
</tr>
<tr>
  <td> 
    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="font-size: 18px; line-height: 26px; color: #707070; font-weight: 500; margin-bottom: 20px;">
      <tbody>
        <tr>
          <td style="text-align: center">
            <img src="{{$message->embed(public_path('images/email_template/new-password.png'))}}" alt="new password">
          </td>
        </tr>
        <tr>
          <td style="text-align: center"> 
            &nbsp;
          </td>
        </tr>
        <tr>
          <td style="text-align: center"> 
            Your New Password has been created.
          </td>
        </tr>
        <tr>
          <td style="text-align: center"> 
            &nbsp;
          </td>
        </tr>
        <tr>
          <td style="text-align: center"> 
           You may now login with your new credentials <a href="{{$data['return_url']}}">here</a>.
         </td>
       </tr>
     </tbody>
   </table>

 </td>
</tr>
@include('mails.footer')