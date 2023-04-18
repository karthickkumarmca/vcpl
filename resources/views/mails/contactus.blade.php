@include('mails.header')
<tr>
  <td valign="top" style="line-height: 0px;">
    <h1 style="text-align: center; margin:50px auto; color: #68BA58; font-weight: 700; font-size:2.8rem; font-family: 'Roboto', sans-serif;">Query Received!</h1>
  </td>   
</tr>
<tr>
  <td> 
    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="font-size: 18px; line-height: 26px; color: #707070; font-weight: 500; margin-bottom: 20px;">
      <tbody>
        <tr>
          <td style="text-align: center">
            <img src="{{$message->embed(public_path('images/email_template/congrats.png'))}}" alt="congarts">
          </td>
        </tr>
        <tr>
          <td style="text-align: center"> 
            &nbsp;
          </td>
        </tr>
        <tr>
          <td style="text-align: center"> 
            A contact query has been submitted. Following are the details.
          </td>
        </tr>
        <tr>
          <td style="text-align: center"> 
            &nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <table width="500" border="0" cellspacing="2" cellpadding="2" align="center"  style="font-size: 20px; line-height: 24px; color: #707070; font-weight: 600">
              <tbody>
                <tr>
                  <td>Name:</td>
                  <td>@isset($data['name']) {{ $data['name'] }} @endisset</td>
                </tr>
                <tr>
                  <td>Mobile No:</td>
                  <td> @isset($data['phone']) {{ $data['phone'] }} @endisset</td>
                </tr>
                <tr>
                  <td>Email:</td>
                  <td> @isset($data['email']) {{ $data['email'] }} @endisset</td>
                </tr>
                <tr>
                  <td>Message:</td>
                  <td> @isset($data['message']) {{ $data['message'] }} @endisset</td>
                </tr>
              </tbody>
            </table>
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