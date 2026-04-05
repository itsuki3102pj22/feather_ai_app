<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>計算結果 - 羽毛価格計算</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: sans-serif;
            max-width: 700px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #27ae60;
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

        .card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .result-table {
            width: 100%;
            border-collapse: collapse;
        }

        .result-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
        }

        .result-table tr:last-child td {
            border-bottom: none;
        }

        .label {
            color: #666;
            width: 40%;
        }

        .value {
            font-weight: bold;
            color: #2c3e50;
        }

        .highlight {
            background: #eaf4fb;
        }

        .highlight .label {
            color: #1a5276;
            font-weight: bold;
        }

        .highlight .value {
            color: #1a5276;
            font-size: 22px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin: 4px;
        }

        .btn-back {
            background: #95a5a6;
            color: white;
        }

        .btn-pdf {
            background: #e74c3c;
            color: white;
        }

        .btn-history {
            background: #8e44ad;
            color: white;
        }

        .comment-box {
            white-space: pre-line;
            line-height: 1.9;
            color: #444;
        }

        canvas {
            max-width: 100%;
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

    <h1>計算結果</h1>

    <div class="card">
        <table class="result-table">
            <tr>
                <td class="label">得意先</td>
                <td class="value">{{ $customerName ?: '―' }}</td>
            </tr>
            <tr>
                <td class="label">羽毛種</td>
                <td class="value">{{ $featherType }}</td>
            </tr>
            <tr>
                <td class="label">産地</td>
                <td class="value">{{ $origin }}産</td>
            </tr>
            <tr>
                <td class="label">ダウン比率</td>
                <td class="value">{{ $downRatio }}%</td>
            </tr>
            <tr>
                <td class="label">ドル円レート</td>
                <td class="value">{{ number_format($usdJpy, 2) }} 円</td>
            </tr>
            <tr>
                <td class="label">原料単価（ドル）</td>
                <td class="value">$ {{ number_format($featherUsd, 2) }} / kg</td>
            </tr>
            <tr>
                <td class="label">仕入れ単価（円）</td>
                <td class="value">{{ number_format($featherJpy, 1) }} 円 / kg</td>
            </tr>
            <tr>
                <td class="label">利益率</td>
                <td class="value">{{ $profitRate }} %</td>
            </tr>
            <tr class="highlight">
                <td class="label">販売単価</td>
                <td class="value">{{ number_format($salePriceJpy, 1) }} 円 / kg</td>
            </tr>
        </table>
    </div>

    <div class="card">
        <h2 style="margin-top:0;">為替変動シミュレーション</h2>
        <p style="font-size:13px;color:#888;">現在レート {{ $usdJpy }}円 を基準に ±15円の販売単価変動</p>
        <canvas id="chart"></canvas>
    </div>

    <div style="margin: 24px 0;">
        <a href="/simulator" class="btn btn-back">← 再計算</a>
        <a href="/simulator/history" class="btn btn-history">履歴一覧</a>
        <form action="/simulator/pdf" method="POST" style="display:inline;">
            @csrf
            <input type="hidden" name="usd_jpy" value="{{ $usdJpy }}">
            <input type="hidden" name="feather_usd" value="{{ $featherUsd }}">
            <input type="hidden" name="feather_type" value="{{ $featherType }}">
            <input type="hidden" name="origin" value="{{ $origin }}">
            <input type="hidden" name="down_ratio" value="{{ $downRatio }}">
            <input type="hidden" name="sale_price_jpy" value="{{ $salePriceJpy }}">
            <input type="hidden" name="customer_name" value="{{ $customerName }}">
            <button type="submit" class="btn btn-pdf" style="border:none;cursor:pointer;font-size:16px;">
                PDF見積書を出力
            </button>
        </form>
    </div>

    <script>
        const base = {{ $usdJpy }};
        const featherUsd = {{ $featherUsd }};
        const featherJpy = {{ $featherJpy }};
        const profitRate = {{ $profitRate }};

        const labels = [],
            sells = [],
            costs = [];
        for (let i = -15; i <= 15; i += 3) {
            const rate = base + i;
            const cost = featherUsd * rate * (featherJpy / (featherUsd * base));
            const sell = cost * (1 + profitRate / 100);
            labels.push(rate + '円');
            costs.push(cost.toFixed(1));
            sells.push(sell.toFixed(1));
        }

        new Chart(document.getElementById('chart'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                        label: '販売単価（円/kg）',
                        data: sells,
                        borderColor: '#27ae60',
                        backgroundColor: 'rgba(39,174,96,0.1)',
                        borderWidth: 2,
                        fill: true,
                    },
                    {
                        label: '仕入れ単価（円/kg）',
                        data: costs,
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231,76,60,0.05)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.dataset.label + ': ¥' + Number(ctx.raw).toLocaleString()
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: v => '¥' + Number(v).toLocaleString()
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>