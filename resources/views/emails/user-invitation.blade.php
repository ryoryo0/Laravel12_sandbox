<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録のご案内</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 30px;
        }
        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3490dc;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #2779bd;
        }
        .info {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ユーザー登録のご案内</h1>

        <p>{{ $invitation->email }} 様</p>

        <p>
            管理者よりユーザー登録のご案内が届きました。<br>
            以下のボタンをクリックして、ユーザー登録を完了してください。
        </p>

        <div style="text-align: center;">
            <a href="{{ $registrationUrl }}" class="button">ユーザー登録を完了する</a>
        </div>

        <div class="info">
            <strong>重要：</strong><br>
            このリンクの有効期限は <strong>{{ config('invitation.token_expiration_hours') }}時間</strong> です。<br>
            有効期限：{{ $invitation->expires_at->format('Y年m月d日 H:i') }}
        </div>

        <p>
            ※このメールに心当たりがない場合は、このメールを無視してください。
        </p>
    </div>

    <div class="footer">
        このメールは自動送信されています。<br>
        返信いただいても対応できませんのでご了承ください。
    </div>
</body>
</html>
