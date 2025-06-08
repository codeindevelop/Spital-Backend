@extends('emails.master')

@section('content')
    <div class="sm-px-4" style="background-color: #ffffff">
        <table align="center" style="margin: 0 auto" cellpadding="0" cellspacing="0" role="none">
            <tr>
                <td class="sm-p-6"
                    style="display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 8px; padding: 24px 36px; border: 1px solid #e2e8f0">

                    <h1 style="margin: 0 0 24px; width: 100%; align-items: flex-start; justify-content: flex-start; font-size: 18px; line-height: 32px; font-weight: 600; color: #0f172a">
                        هشدار: تلاش ناموفق برای ورود به حساب شما
                    </h1>
                    <p style="margin: 0 0 24px; font-size: 16px; line-height: 24px; color: #475569">
                        {{ $first_name }} عزیز،
                        <br>
                        یک تلاش ناموفق برای ورود به حساب کاربری شما با ایمیل <strong>{{ $targetUser->email }}</strong> انجام شده است. اگر این اقدام توسط شما انجام نشده، لطفاً فوراً با تیم پشتیبانی تماس بگیرید یا از طریق دکمه زیر گزارش دهید.
                    </p>
                    <div style="display: flex; width: 100%; flex-direction: column; color: #475569; margin-bottom: 24px;">
                        <p>آدرس IP: {{ $ip }}</p>
                        <p>تاریخ: {{ $date }}</p>
                        <p>ساعت: {{ $time }}</p>
                    </div>
                    <a href="{{ url('/contact/support') }}"
                       style="display: inline-block; padding: 12px 24px; background-color: #dc2626; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: 600;">
                        گزارش تلاش مشکوک
                    </a>
                    <div role="separator" style="line-height: 50px"> </div>
                    <p style="margin: 0; width: 100%; align-items: flex-start; justify-content: flex-start; font-size: 16px; line-height: 24px; color: #475569">
                        با تشکر از هوشیاری شما،
                        <br>
                        <span style="font-weight: 600">استودیو برنامه‌نویسی ابریکُد</span>
                    </p>
                    <div role="separator"
                         style="height: 1px; line-height: 1px; background-color: #cbd5e1; margin-top: 24px; margin-bottom: 24px">
                         
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection
