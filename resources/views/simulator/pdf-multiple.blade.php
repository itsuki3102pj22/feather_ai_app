<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'ipaexg';
            font-style: normal;
            font-weight: normal;
            src: url("{{ storage_path('fonts/ipaexg.ttf') }}") format("truetype");
        }
        @font-face {
            font-family: 'ipaexg';
            font-style: normal;
            font-weight: bold;
            src: url("{{ storage_path('fonts/ipaexg.ttf') }}") format("truetype");
        }
        body, h1, h2, p, td, th, div, span {
            font-family: 'ipaexg', sans-serif;
            font-weight: normal;
        }
        body { padding: 40px; color: #1a1a1a; font-size: 13px; line-height: 1.8; }
        .doc-number { text-align: right; font-size: 11px; color: #888; margin-bottom: 10px; }
        .doc-title { font-size: 22px; text-align: center; border-bottom: 2px solid #1a1a1a; padding-bottom: 8px; margin-bottom: 30px; }
        .to-section { margin-bottom: 10px; font-size: 16px; }
        .from-section { text-align: right; font-size: 12px; color: #444; margin-bottom: 30px; }
        .intro { margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        th { background: #2c3e50; color: white; padding: 10px; text-align: left; font-weight: normal; font-size: 12px; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        .total-row td { background: #eaf4fb; font-size: 15px; border-top: 2px solid #2c3e50; font-weight: normal; }
        .note { font-size: 11px; color: #888; margin-top: 6px; }
        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="doc-number">発行日：{{ now()->format('Y年m月d日') }}</div>

    <div class="doc-title">御　見　積　書</div>

    <div class="to-section">{{ $customerName ?: 'お客様' }}　御中</div>

    <div class="from-section">
        自社名称<br>
        担当：___________<br>
        TEL：___________
    </div>

    <div class="intro">下記の通りお見積り申し上げます。</div>

    <table>
        <thead>
            <tr>
                <th>品種</th>
                <th>産地</th>
                <th>ダウン比率</th>
                <th>お見積り単価（税別）</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['feather_type'] }}</td>
                <td>{{ $item['origin'] }}</td>
                <td>{{ $item['down_ratio'] }}%</td>
                <td>¥{{ number_format($item['sell_price'], 0) }} / kg</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="note">※ 本見積書の有効期限は発行日より30日間とします。</p>
    <p class="note">※ 為替レートの変動により価格が変更になる場合があります。</p>
    <p class="note">※ 数量・納期については別途ご相談ください。</p>

    <div class="footer">
        この見積書に関するお問い合わせはご担当者までご連絡ください
    </div>
</body>
</html>