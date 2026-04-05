<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <style>
        /* 1. フォントの定義 */
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

        body,
        h1,
        h2,
        h3,
        p,
        div,
        span {
            font-family: 'ipaexg', sans-serif;
        }

        body {
            padding: 40px;
            color: #2c3e50;
            line-height: 1.5;
        }

        h1 {
            font-size: 22px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 8px;
            margin: 0 0 20px 0;
        }

        h2 {
            font-size: 16px;
            margin-top: 30px;
            color: #3498db;
        }

        .info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
        }

        .comment {
            line-height: 1.8;
            white-space: pre-wrap;
            /* 改行をAIの回答通りに反映 */
        }

        .footer {
            margin-top: 60px;
            font-size: 10px;
            color: #999;
            text-align: right;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <h1>羽毛価格分析レポート</h1>

    <h2>基本情報</h2>
    <div class="info">
        <p>作成日：{{ now()->format('Y年m月d日') }}</p>
        <p>ドル円レート：{{ $usdJpy }}円</p>
        <p>羽毛単価：{{ $featherUsd }}ドル/kg</p>
        <p>円建て換算単価：{{ number_format($usdJpy * $featherUsd, 1) }}円/kg</p>
    </div>

    <h2>AI市況分析コメント</h2>
    <div class="comment">{{ $comment }}</div>

    <div class="footer">
        羽毛価格シミュレーター 自動生成レポート
    </div>
</body>

</html>