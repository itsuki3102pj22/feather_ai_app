<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>羽毛価格計算</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 640px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #2980b9;
            padding-bottom: 10px;
        }

        nav {
            margin-bottom: 24px;
        }

        nav a {
            margin-right: 16px;
            color: #2980b9;
            text-decoration: none;
            font-weight: bold;
        }

        .form-group {
            margin: 16px 0;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            color: #444;
        }

        select,
        input[type=number],
        input[type=text] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .hint {
            font-size: 12px;
            color: #999;
            margin-top: 4px;
        }

        .rate-box {
            background: #eaf4fb;
            border: 1px solid #aed6f1;
            border-radius: 6px;
            padding: 12px;
            margin: 16px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title {
            background: #2980b9;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: bold;
            margin: 24px 0 12px;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background: #1e8449;
        }
    </style>
</head>

<body>
    <nav>
        <a href="/simulator">価格計算</a>
        <a href="/price-chart">価格推移</a>
        <a href="/customers">得意先管理</a>
        <a href="/simulator/history">履歴</a>
    </nav>

    <h1>羽毛価格計算</h1>

    <div class="rate-box">
        <span>現在のドル円レート：</span>
        <strong id="rate-display" style="font-size:20px;color:#2980b9;">取得中...</strong>
        <span style="font-size:12px;color:#888;">（自動取得）</span>
    </div>

    <form action="/simulator/analyze" method="POST">
        @csrf
        <input type="hidden" name="usd_jpy" id="usd_jpy_input" value="">

        <div class="section-title">羽毛情報</div>

        <div class="form-group">
            <label>羽毛の種類</label>
            <select name="feather_type">
                <option value="ホワイトダック">ホワイトダック（White Duck）</option>
                <option value="ホワイトグース">ホワイトグース（White Goose）</option>
                <option value="グレーダック">グレーダック（Grey Duck）</option>
                <option value="グレーグース">グレーグース（Grey Goose）</option>
            </select>
        </div>

        <div class="form-group">
            <label>産地</label>
            <select name="origin">
                <option value="中国">中国</option>
                <option value="フランス">フランス</option>
                <option value="ロシア">ロシア</option>
                <option value="イタリア">イタリア</option>
                <option value="ウクライナ">ウクライナ</option>
            </select>
        </div>
        <div class="form-group">
            <label>羽毛原料単価（ドル/kg）</label>
            <input type="number" name="feather_usd" value="95" step="0.01" min="0">
            <div class="hint">ホワイトダック85%の現地仕入れ価格</div>
        </div>

        <div class="section-title">販売設定</div>

        <div class="form-group">
            <label>自社利益率（%）</label>
            <input type="number" name="profit_rate" value="10" step="0.1" min="0" max="100">
            <div class="hint">PDF見積書には表示されません</div>
        </div>

        <div class="form-group">
            <label>得意先名（任意）</label>
            <input type="text" name="customer_name" placeholder="例：○○寝具株式会社">
        </div>

        <button type="submit">計算する</button>
    </form>

    <script>
        fetch('https://open.er-api.com/v6/latest/USD')
            .then(res => res.json())
            .then(data => {
                const rate = data.rates.JPY.toFixed(2);
                document.getElementById('rate-display').textContent = rate + ' 円';
                document.getElementById('usd_jpy_input').value = rate;
            })
            .catch(() => {
                document.getElementById('rate-display').textContent = '取得失敗';
            });
    </script>
</body>

</html>