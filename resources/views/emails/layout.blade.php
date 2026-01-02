<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $subject ?? 'RTFM.guide' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;600;700;800&family=Onest:wght@300;400;500;600;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Onest', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #fafaf9;
            color: #404040;
            line-height: 1.6;
        }

        .email-wrapper {
            width: 100%;
            background-color: #fafaf9;
            padding: 40px 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #be185d 0%, #9f1239 100%);
            padding: 40px 32px;
            text-align: center;
        }

        .email-logo {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: -0.02em;
        }

        .email-logo-accent {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .email-content {
            padding: 48px 32px;
        }

        .email-title {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #262626;
            margin: 0 0 16px 0;
            line-height: 1.3;
        }

        .email-text {
            font-size: 16px;
            color: #525251;
            margin: 0 0 24px 0;
            line-height: 1.7;
        }

        .email-button {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(135deg, #be185d 0%, #9f1239 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            margin: 8px 0;
            box-shadow: 0 10px 15px -3px rgb(190 24 93 / 0.3);
            transition: transform 0.2s;
        }

        .email-button:hover {
            transform: translateY(-2px);
        }

        .email-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e7e5e4, transparent);
            margin: 32px 0;
        }

        .email-footer {
            background-color: #f5f5f4;
            padding: 32px;
            text-align: center;
            border-top: 1px solid #e7e5e4;
        }

        .email-footer-text {
            font-size: 14px;
            color: #737372;
            margin: 8px 0;
        }

        .email-footer-link {
            color: #be185d;
            text-decoration: none;
            font-weight: 500;
        }

        .email-footer-link:hover {
            color: #9f1239;
        }

        .email-subcopy {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e7e5e4;
        }

        .email-subcopy-text {
            font-size: 13px;
            color: #a3a3a2;
            line-height: 1.6;
        }

        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 20px 10px;
            }

            .email-container {
                border-radius: 12px;
            }

            .email-header {
                padding: 32px 24px;
            }

            .email-content {
                padding: 32px 24px;
            }

            .email-title {
                font-size: 20px;
            }

            .email-button {
                display: block;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <a href="{{ config('app.url') }}" class="email-logo">
                    RTFM<span class="email-logo-accent">.guide</span>
                </a>
            </div>

            <!-- Content -->
            <div class="email-content">
                @yield('content')
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p class="email-footer-text">
                    <strong>RTFM.guide</strong> - Read The Friendly Manual
                </p>
                <p class="email-footer-text">
                    <a href="{{ config('app.url') }}" class="email-footer-link">Visit Website</a>
                    &nbsp;&nbsp;•&nbsp;&nbsp;
                    <a href="{{ config('app.url') }}/guides" class="email-footer-link">Browse Guides</a>
                </p>

                @hasSection('subcopy')
                <div class="email-subcopy">
                    <p class="email-subcopy-text">
                        @yield('subcopy')
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
