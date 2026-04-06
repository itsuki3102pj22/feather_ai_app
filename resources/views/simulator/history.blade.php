<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>価格推移履歴</title>
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
        }

        .card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2980b9;
            color: white;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f0f0f0;
        }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .white {
            background: #eaf4fb;
            color: #1a5276;
        }

        .grey {
            background: #f4f6f7;
            color: #4d5656;
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
    <h1>価格推移履歴</h1>
    <a href="/simulator">← シミュレーターに戻る</a>

    <div class="card">
        <h2>月別平均価格推移</h2>
        @if($monthly->count() > 0)
        <canvas id="monthlyChart"></canvas>
        @else
        <p style="color:#999;">データが蓄積されると月別グラフが表示されます。</p>
        @endif
    </div>

    <div class="card">
        <h2>直近の分析履歴</h2>
        <table>
            <thead>
                <tr>
                    <th>日時</th>
                    <th>種類</th>
                    <th>産地</th>
                    <th>ドル単価</th>
                    <th>為替</th>
                    <th>円換算単価</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $h)
                <tr>
                    <td>{{ $h->created_at->format('Y/m/d H:i') }}</td>
                    <td>
                        <span class="badge {{ $h->feather_type === 'ホワイトダック' ? 'white' : 'grey' }}">
                            {{ $h->feather_type }}
                        </span>
                    </td>
                    <td>{{ $h->origin }}</td>
                    <td>$ {{ number_format($h->feather_usd, 2) }}</td>
                    <td>{{ number_format($h->usd_jpy, 2) }}円</td>
                    <td><strong>{{ number_format($h->price_jpy, 1) }}円/kg</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        @if($monthly->count() > 0)
        const labels = @json($monthly->pluck('month'));
        const prices = @json($monthly->pluck('avg_price'));
        const rates = @json($monthly->pluck('avg_rate'));

        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: '平均円換算単価（円/kg）',
                        data: prices,
                        borderColor: '#2980b9',
                        backgroundColor: 'rgba(41,128,185,0.1)',
                        borderWidth: 2,
                        yAxisID: 'y',
                        fill: true,
                    },
                    {
                        label: 'ドル円レート（円）',
                        data: rates,
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231,76,60,0.05)',
                        borderWidth: 2,
                        yAxisID: 'y2',
                        fill: false,
                        borderDash: [5, 5],
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        title: {
                            display: true,
                            text: '円/kg'
                        }
                    },
                    y2: {
                        type: 'linear',
                        position: 'right',
                        title: {
                            display: true,
                            text: 'ドル円'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
        @endif
    </script>
</body>

</html>