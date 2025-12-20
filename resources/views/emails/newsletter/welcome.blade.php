<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Newsletter</title>
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="background-color: #f4f6f9; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                    style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">

                    <!-- Header with Gradient -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #1e53a4 0%, #0d2f5e 100%); padding: 40px 30px; text-align: center;">
                            <h1
                                style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                🎉 Welcome Aboard!
                            </h1>
                            <p style="margin: 10px 0 0 0; color: #e6f0ff; font-size: 16px; font-weight: 400;">
                                Pinnacle Capital Group Newsletter
                            </p>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 20px 0; color: #1e53a4; font-size: 24px; font-weight: 700;">
                                Thank You for Subscribing!
                            </h2>

                            <p style="margin: 0 0 20px 0; color: #333333; font-size: 16px; line-height: 1.6;">
                                We're thrilled to have you as part of the <strong>Pinnacle Capital Group</strong>
                                community. You've taken the first step toward staying informed with exclusive investment
                                insights, market updates, and financial tips.
                            </p>

                            <!-- Email Info Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="background-color: #f8fafc; border-left: 4px solid #1e53a4; border-radius: 6px; margin: 25px 0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p
                                            style="margin: 0; color: #666666; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Subscribed Email
                                        </p>
                                        <p
                                            style="margin: 8px 0 0 0; color: #1e53a4; font-size: 16px; font-weight: 700;">
                                            {{ $email }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="margin: 35px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="https://pinnaclealts.com/"
                                            style="display: inline-block; background: linear-gradient(135deg, #1e53a4 0%, #0d2f5e 100%); color: #ffffff; padding: 16px 40px; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 16px; box-shadow: 0 4px 12px rgba(30, 83, 164, 0.3); transition: all 0.3s;">
                                            Visit Our Website
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 25px 0 0 0; color: #666666; font-size: 15px; line-height: 1.6;">
                                We're committed to providing you with valuable content. If you have any questions or
                                feedback, feel free to reach out to us anytime.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 30px; border-top: 1px solid #e5e7eb;">
                            <p
                                style="margin: 0 0 15px 0; color: #999999; font-size: 13px; line-height: 1.6; text-align: center;">
                                You received this email because you subscribed to our newsletter at<br>
                                <strong>Pinnacle Capital Group</strong>
                            </p>

                            <p style="margin: 0; color: #999999; font-size: 12px; text-align: center;">
                                © 2026 Pinnacle Capital Group. All rights reserved.
                            </p>

                            <!-- Unsubscribe Link -->
                            <p style="margin: 15px 0 0 0; text-align: center;">
                                <a href="{{ config('app.url') }}/unsubscribe?email={{ urlencode($email) }}"
                                    style="color: #1e53a4; font-size: 12px; text-decoration: underline;">
                                    Unsubscribe from this list
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
