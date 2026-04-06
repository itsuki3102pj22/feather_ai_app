<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>羽毛価格推移</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: sans-serif;
            max-width: 900px;
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

        .card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .tab-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 6px 6px 0 0;
            cursor: pointer;
            font-size: 15px;
            background: #ddd;
            color: #555;
        }

        .tab-btn.active {
            background: #2980b9;
            color: white;
        }

        .rate-box {
            background: #eaf4fb;
            border: 1px solid #aed6f1;
            border-radius: 6px;
            padding: 10px 16px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .form-row .form-group {
            flex: 1;
            min-width: 140px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 4px;
            color: #555;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-primary {
            background: #2980b9;
            color: white;
        }

        .btn-green {
            background: #27ae60;
            color: white;
        }

        .legend {
            display: flex;
            gap: 20px;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 4px;
            vertical-align: middle;
        }

        .alert {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            padding: 10px 16px;
            margin-bottom: 16px;
            color: #155724;
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

    <h1>羽毛価格推移</h1>

    @if(session('success'))
    <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="rate-box">
        <span>現在のドル円レート：</span>
        <strong id="rate-display" style="font-size:20px;color:#2980b9;">取得中...</strong>
        <span style="font-size:12px;color:#888;">（自動取得）</span>
    </div>

    <!-- グラフ -->
    <div class="card">
        <div style="margin-bottom:12px;">
            <button class="tab-btn active" onclick="switchTab('monthly')">月別</button>
            <button class="tab-btn" onclick="switchTab('weekly')">週別</button>
        </div>

        <div class="legend">
            <span><span class="legend-dot" style="background:#e74c3c;"></span>ホワイトダック85%</span>
            <span><span class="legend-dot" style="background:#2980b9;"></span>グレーダック（WD×70%）</span>
        </div>

        <canvas id="monthlyChart" style="display:block;"></canvas>
        <canvas id="weeklyChart" style="display:none;"></canvas>
    </div>

    <!-- コメント欄 -->
    <div class="card">
        <h2 style="margin-top:0;">市況コメント</h2>
        @if($latestComment)
        @if($latestComment->manual_comment)
        <div style="background:white;border-left:4px solid #27ae60;padding:12px;border-radius:4px;margin-bottom:12px;white-space:pre-line;">
            {{ $latestComment->manual_comment }}
        </div>
        @endif
        @else
        <p style="color:#999;">コメントはまだありません。</p>
        @endif

        <form action="/price-chart/comment" method="POST">
            @csrf
            <div class="form-group">
                <label>コメントを入力・更新</label>
                <textarea name="manual_comment" rows="4" placeholder="市場の動き・為替の影響・今後の見通しなど">{{ $latestComment?->manual_comment }}</textarea>
            </div>
            <button type="submit" class="btn btn-green" style="margin-top:8px;">コメントを保存</button>
        </form>
    </div>

    <!-- データ入力 -->
    <div class="card">
        <h2 style="margin-top:0;">価格データを登録</h2>
        <form action="/price-chart/store" method="POST">
            @csrf
            <input type="hidden" name="usd_jpy" id="store_usd_jpy" value="">
            <div class="form-row">
                <div class="form-group">
                    <label>記録日</label>
                    <input type="date" name="record_date" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label>種別</label>
                    <select name="period_type">
                        <option value="monthly">月別</option>
                        <option value="weekly">週別</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>WD85% ドル単価（$/kg）</label>
                    <input type="number" name="white_duck_usd" step="0.01" placeholder="例：95.0" required>
                </div>
                <div class="form-group" style="display:flex;align-items:flex-end;">
                    <button type="submit" class="btn btn-primary" style="width:100%;">登録</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // 為替自動取得
        let currentRate = 150;
        fetch('https://open.er-api.com/v6/latest/USD')
            .then(r => r.json())
            .then(d => {
                currentRate = d.rates.JPY;
                document.getElementById('rate-display').textContent = currentRate.toFixed(2) + ' 円';
                document.getElementById('store_usd_jpy').value = currentRate.toFixed(2);
            });

        // グラフデータ
        const monthlyData = @json($monthly);
        const weeklyData = @json($weekly);

        function buildChart(canvasId, data) {
            const labels = data.map(d => d.record_date.substring(0, 7));
            const white = data.map(d => d.white_duck_jpy);
            const grey = data.map(d => d.grey_duck_jpy);

            return new Chart(document.getElementById(canvasId), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                            label: 'ホワイトダック85%（円/kg）',
                            data: white,
                            borderColor: '#e74c3c',
                            backgroundColor: 'rgba(231,76,60,0.08)',
                            borderWidth: 2,
                            pointRadius: 4,
                            fill: true,
                        },
                        {
                            label: 'グレーダック（円/kg）',
                            data: grey,
                            borderColor: '#2980b9',
                            backgroundColor: 'rgba(41,128,185,0.06)',
                            borderWidth: 2,
                            pointRadius: 4,
                            fill: true,
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
        }

        const mChart = buildChart('monthlyChart', monthlyData);
        const wChart = buildChart('weeklyChart', weeklyData);

        function switchTab(type) {
            document.getElementById('monthlyChart').style.display = type === 'monthly' ? 'block' : 'none';
            document.getElementById('weeklyChart').style.display = type === 'weekly' ? 'block' : 'none';
            document.querySelectorAll('.tab-btn').forEach((b, i) => {
                b.classList.toggle('active', (i === 0 && type === 'monthly') || (i === 1 && type === 'weekly'));
            });
        }
    </script>
</body>

</html>