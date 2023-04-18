@include('mails.header')
<!-- <table style="border: 1px solid #25538F;"> -->
    <table width="100%">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0" style="padding:5%;background-repeat:no-repeat;background-size:contain;">
                    <tbody>
                        <tr>
                            <td>
                                <b>Dear {{$data['name']}}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        @if($data['status']==1 && $data['payment_status_value']!=1)

                        <tr>
                            <td>
                                Your event authorization application has been verified and {{$data['statusname']}}.
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        @if($data['application_type_value']!=3)
                        <tr>
                            <td>
                                To login to your account kindly <a href="{{env('FRONT_URL')}}" target="_blank">click here</a> and use the same email/ mobile number used to apply for authorization and buy your tickets..
                            </td>
                        </tr>
                        @else

                        <tr>
                            <td>
                                To login to your account kindly <a href="{{env('FRONT_URL')}}" target="_blank">click here</a> and use the same email/ mobile number used to apply for authorization and download your ticket..
                            </td>
                        </tr>

                        @endif
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        

                        @endif

                        @if($data['status']==2)
                        <tr>
                            <td>
                                We regret to inform you that Your application for the MLGRD Event Authorization has been denied.
                            </td>
                        </tr>

                        @endif

                        @if($data['status']==4)
                        <tr>
                            <td>
                                Your application has been verified and requires further information for approval. Please login to your account and update the requested information.
                            </td>
                        </tr>

                        @endif

                        @if($data['status']==5 || $data['fifa_status']==5)

                        <tr>
                            <td>
                                Your event authorization application has been verified and Complimentary.
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        @if($data['application_type_value']!=3)
                        <tr>
                            <td>
                                To login to your account kindly <a href="{{env('FRONT_URL')}}" target="_blank">click here</a> and use the same email/ mobile number used to apply for authorization and buy your tickets..
                            </td>
                        </tr>
                        @else

                        <tr>
                            <td>
                                To login to your account kindly <a href="{{env('FRONT_URL')}}" target="_blank">click here</a> and use the same email/ mobile number used to apply for authorization and download your ticket..
                            </td>
                        </tr>

                        @endif
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        

                        @endif



                        @if($data['payment_status_value']==1)

                        <tr>
                            <td>
                            Your payment has been received.
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>


                        <tr>
                            <td>
                            To login to your account kindly <a href="{{env('FRONT_URL')}}" target="_blank">click here</a> and use the same email/mobile number used to apply for authorization and download your ticket.

                            </td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                        </tr>


                        @endif
                        
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        
                        <tr>
                            <td>&nbsp;</td>
                        </tr>


                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                *** This is an auto-generated email. Please do not reply to this email. ***
                            </td>
                        </tr>

                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    @include('mails.footer')