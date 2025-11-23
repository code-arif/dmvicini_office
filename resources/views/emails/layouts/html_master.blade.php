<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $title ?? "Pinnacle Alt's Platform" }}</title>

    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->

    <style type="text/css">
        /* Outlook-safe styles */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
    </style>

    <!-- Web-safe font stack -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            color: #2d3748;
        }
    </style>
</head>

<body width="100%" bgcolor="#f5f7fa" style="margin: 0; padding: 0; background-color: #f5f7fa;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f5f7fa">
        <tr>
            <td align="center" style="padding: 20px 10px;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
                    style="max-width: 600px; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <tr>
                        <td align="center" bgcolor="#172971"
                            style="padding: 40px 20px 30px; color: #ffffff; text-align: center; font-family: Arial, sans-serif;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                style="width: 100%; max-width: 500px;">
                                <tr>
                                    <td align="center" style="text-align: center;">
                                        <h1
                                            style="font-size: 24px; font-weight: 600; margin: 0; line-height: 1.3; font-family: Arial, sans-serif; text-align: center; color: #ffffff;">
                                            Pinnacle Alt's Platform
                                        </h1>
                                        <div style="margin-top: 20px; text-align: center;">
                                            <img src="{{ $message->embed(public_path('default/logo.png')) }}"
                                                alt="Pinnacle Alt's Platform Logo" width="120"
                                                style="display: inline-block; max-width: 100%; height: auto;">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td
                            style="padding: 40px 30px; font-family: Arial, sans-serif; font-size: 15px; line-height: 1.6; color: #4a5568;">
                            @yield('content')
                        </td>
                    </tr>

                    <tr>
                        <td bgcolor="#f7fafc" align="center"
                            style="padding: 30px; text-align: center; font-family: Arial, sans-serif; font-size: 13px; color: #718096; border-top: 1px solid #e2e8f0;">

                            <p style="margin: 0 0 10px; font-family: Arial, sans-serif;">&copy; {{ date('Y') }}
                                Pinnacle Alt's Platform. All rights reserved.</p>

                            {{-- <p style="margin: 0 0 15px; font-family: Arial, sans-serif;">
            <a href="{{ config('app.url') }}/privacy" style="color: #172971; text-decoration: none;">Privacy Policy</a> •
            <a href="{{ config('app.url') }}/terms" style="color: #172971; text-decoration: none;">Terms of Use</a>
        </p> --}}

                            <p style="margin: 0; font-size: 12px; color: #a0aec0; font-family: Arial, sans-serif;">
                                This is an automated message. Please do not reply.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
