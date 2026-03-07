<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <title>Modern Email • No Tables</title>

    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background: #f8fafc;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            padding: 60px 40px 48px;
            text-align: center;
            color: white;
        }

        .content {
            padding: 48px 40px 56px;
        }

        .highlight-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 32px;
            margin: 32px 0;
        }

        .price {
            font-size: 34px;
            font-weight: 800;
            color: #6366f1;
            margin: 8px 0;
        }

        .due {
            font-size: 20px;
            font-weight: 600;
            color: #ef4444;
        }

        .btn {
            display: inline-block;
            background: #6366f1;
            color: white;
            font-size: 16px;
            font-weight: 600;
            padding: 16px 40px;
            border-radius: 10px;
            text-decoration: none;
            margin: 32px auto;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #4f46e5;
        }

        .footer {
            background: #0f172a;
            color: #cbd5e1;
            padding: 48px 40px 40px;
            text-align: center;
            font-size: 14px;
        }

        .social a {
            color: #94a3b8;
            margin: 0 14px;
            font-size: 20px;
            text-decoration: none;
        }

        .muted {
            color: #64748b;
            font-size: 15px;
            line-height: 1.65;
        }

        h1 {
            margin: 0 0 24px;
            font-size: 28px;
            font-weight: 700;
        }

        @media (max-width: 600px) {

            .content,
            .header,
            .footer {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }

            .price {
                font-size: 28px;
            }
        }

        @media (prefers-color-scheme: dark) {
            body {
                background: #0f172a !important;
            }

            .container {
                background: #1e293b !important;
            }

            .content {
                color: #e2e8f0 !important;
            }

            .highlight-box {
                background: #334155 !important;
            }

            .muted {
                color: #94a3b8 !important;
            }

            .footer {
                background: #020617 !important;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Header -->
        <div class="header">
            <div style="font-size:32px; font-weight:800; letter-spacing:-1px; margin-bottom:8px;">
                {{ $subject ?? 'Email' }}
            </div>
            <div style="font-size:16px; opacity:0.9;">
            </div>
        </div>
