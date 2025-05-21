@extends('emails.master')

@section('content')

    <table style="width: 100%" cellpadding="0" cellspacing="0" role="none">
        <tr>
            <td class="sm-p-6"
                style="display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 8px;   padding: 24px 36px; border: 1px solid #e2e8f0">


                <h1 style="margin: 0 0 24px; width: 100%; align-items: flex-start; justify-content: flex-start; font-size: 16px; line-height: 32px; font-weight: 600; color: #0f172a">
                    سلام {{ $first_name }} عزیز
                </h1>
                <p style="margin: 0 0 24px; font-size: 16px; line-height: 24px; color: #475569">
                    به خانواده ابریکُد خوش آمدید ، ثبت نام شما با موفقیت در سامانه
                    ابریکُد انجام گردیده ، جهت فعالسازی حساب کاربری خود لطفا روی
                    گزینه فعالسازی حساب کاربری کلیک نمایید
                </p>
                <div>
                    <a href={{ env('EMAIL_ACTIVE_LINK_PREFIX') . $activation_token }} target="_blank"
                       style="display: inline-block; text-decoration: none; padding: 16px 24px; font-size: 16px; line-height: 1; border-éradius: 4px; color: #fffffe; margin-left: 0; margin-right: 0; align-items: center; justify-content: center; background-color: #38bdf8"
                       class="hover-bg-slate-800">
                        <!--[if mso]><i style="mso-font-width: 150%; mso-text-raise: 31px" hidden>&emsp;</i><![endif]-->
                        <span style="mso-text-raise: 16px">فعالسازی حساب کاربری</span>
                        <!--[if mso]><i hidden style="mso-font-width: 150%">&emsp;&#8203;</i><![endif]-->
                    </a>
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
                <p class="mso-break-all" style="margin: 0; text-align: center; color: #475569">
                    در صورتی که برای فعالسازی حساب کاربری مشکلی دارید ، لینک زیر
                    را در مرورگر خود کپی کرده و وارد نمایید
                    <br>
                    <br>
                    <a href={{ env('EMAIL_ACTIVE_LINK_PREFIX') . $activation_token }} target="_blank"
                       style="font-size: 12px; color: #1e293b; text-decoration: underline">https://abrecode.com/?token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0</a>
                </p>
            </td>
        </tr>
    </table>

@endsection
