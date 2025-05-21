@extends('emails.master')

@section('content')
    <div class="sm-px-4" style="background-color: #ffffff">
        <table align="center" style="margin: 0 auto" cellpadding="0" cellspacing="0" role="none">
            <tr>
                <td class="sm-p-6"
                    style="display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 8px;   padding: 24px 36px; border: 1px solid #e2e8f0">


                    <h1 style="margin: 0 0 24px; width: 100%; align-items: flex-start; justify-content: flex-start; font-size: 16px; line-height: 32px; font-weight: 600; color: #0f172a">
                        سلام {{ $first_name }} عزیز
                    </h1>
                    <p style="margin: 0 0 24px; font-size: 16px; line-height: 24px; color: #475569">
                        شما در تاریخ زیر وارد حساب کاربری خود شده اید . این ایمیل جهت اطلاع رسانی به شما ارسال شده است و
                        اگر شما
                        این عملیات را انجام نداده اید لطفا اطلاع دهید
                    </p>
                    <div style="display: flex; width: 100%; flex-direction: column; color: #475569">
                        <p>ادرس ای پی : {{ $ip }}  </p>
                        <p>تاریخ ورود : {{ $date }}</p>
                        <p>ساعت ورود : {{ $time }}</p>
                    </div>
                    <div role="separator" style="line-height: 50px">&zwj;</div>
                    <p style="margin: 0; width: 100%; align-items: flex-start; justify-content: flex-start; font-size: 16px; line-height: 24px; color: #475569">
                        با تشکر از انتخاب شما
                        <br>
                        <span style="font-weight: 600">استودیو برنامه نویسی ابریکُد</span>
                    </p>
                    <div role="separator"
                         style="height: 1px; line-height: 1px; background-color: #cbd5e1; margin-top: 24px; margin-bottom: 24px">
                        &zwj;
                    </div>

                </td>
            </tr>
        </table>
    </div>

@endsection
