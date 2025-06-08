<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <style>
        .hover-bg-slate-800:hover {
            background-color: #1e293b !important;
        }
        @media (max-width: 600px) {
            .sm-p-6 { padding: 24px !important; }
            .sm-px-4 { padding-left: 16px !important; padding-right: 16px !important; }
            .sm-px-6 { padding-left: 24px !important; padding-right: 24px !important; }
        }
        /* بهبود سازگاری با کلاینت‌های ایمیل */
        body, table, td, p, a, h1, h2, h3, h4, h5, h6 { font-family: Tahoma, Arial, sans-serif !important; }
        a { text-decoration: none; }
        img { border: 0; outline: none; }
    </style>
</head>
<body style="margin: 0; width: 100%; background-color: #f8fafc; padding: 0; font-family: Tahoma, Arial, sans-serif; word-break: break-word">
<div role="article" aria-roledescription="email" aria-label lang="fa">
    <div class="sm-px-4" style="background-color: #f8fafc">
        <table align="center" style="margin: 0 auto; max-width: 600px;" cellpadding="0" cellspacing="0" role="none">
            <tr>
                <td style="width: 100%;">
                    <div role="separator" style="line-height: 24px">&nbsp;</div>
                    <table style="width: 100%;" cellpadding="0" cellspacing="0" role="none">
                        <tr>
                            <td class="sm-p-6"
                                style="display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 8px; background-color: #ffffff; padding: 24px 36px; border: 1px solid #e2e8f0;">
                                <a href="https://abrecode.com">
                                    <img src="{{ url('/assets/img/logo.png') }}" width="50" alt="ابریکد" style="max-width: 100%; vertical-align: middle;">
                                </a>
                                <div role="separator" style="line-height: 24px">&nbsp;</div>
                                @yield('content')
                            </td>
                        </tr>
                    </table>
                    <table style="margin-top: 16px; width: 100%; text-align: center;" cellpadding="0" cellspacing="0" role="none">
                        <tr>
                            <td class="sm-px-6 sm-p-6"
                                style="margin-top: 20px; border-radius: 8px; background-color: #ffffff; padding: 24px 36px; border: 1px solid #e2e8f0;">
                                <p style="color: #64748b; font-size: 14px; line-height: 22px;">
                                    ابریکُد اولین و بزرگ‌ترین مارکت تخصصی برنامه‌نویسی و آموزش برنامه‌نویسی برای ورود به بازار کار در ایران
                                </p>
                                <div style="margin: 24px 0; display: flex; justify-content: center; gap: 20px;">
                                    <a href="https://www.instagram.com/abrecode/"><img src="{{ url('/assets/img/Aut_Logo_Instagram_grey_60x59.png') }}" width="30" alt="instagram"></a>
                                    <a href="https://www.x.com/abrecode_fa"><img src="{{ url('/assets/img/Aut_Logo_X.png') }}" width="30" alt="x"></a>
                                    <a href="https://www.facebook.com/abrecode"><img src="{{ url('/assets/img/Aut_Logo_Facebook_grey_59x59.png') }}" width="30" alt="facebook"></a>
                                    <a href="https://www.t.me/abrecode"><img src="{{ url('/assets/img/telegram-xxl.png') }}" width="30" alt="telegram"></a>
                                    <a href="https://www.youtube.com/@abrecode"><img src="{{ url('/assets/img/gray_circle_youtube_icon.png') }}" width="30" alt="youtube"></a>
                                </div>
                                <div style="display: flex; justify-content: center; gap: 20px; font-size: 14px; color: #1e293b;">
                                    <a href="https://abrecode.com">وب‌سایت ابریکُد</a>
                                    <a href="https://abrecode.com/blog">وبلاگ</a>
                                    <a href="https://www.abrecode.com/aboutus/legal/terms">شرایط استفاده</a>
                                    <a href="https://www.abrecode.com/aboutus/legal/privacy-policy">حریم خصوصی</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
