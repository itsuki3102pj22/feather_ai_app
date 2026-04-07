<!DOCTYPE html>
<html class="light" lang="ja">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Aerostat - 価格推移履歴</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@300;500;700;800&family=Material+Symbols+Outlined:wght@100..700" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "surface": "#f8fafc",
                        "primary": "#002705",
                        "secondary": "#455f88",
                        "accent-green": "#61b05e",
                        "navy-dark": "#1a365d",
                        "chart-blue": "#3b82f6",
                        "chart-red": "#ef4444"
                    },
                    fontFamily: {
                        "headline": ["Manrope", "sans-serif"],
                        "body": ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-active {
            background: white;
            color: #61b05e !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-slate-900 font-body min-h-screen flex antialiased">
    <aside class="h-screen w-64 fixed left-0 top-0 z-40 bg-[#f8fafc] flex flex-col p-6 gap-2 border-r border-slate-100">
        <div class="mb-10 px-2 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-white">cloud</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-navy-dark tracking-widest font-headline uppercase">AEROSTAT</h1>
                <p class="text-[10px] text-secondary font-bold tracking-tighter uppercase">Market Intelligence</p>
            </div>
        </div>
        <nav class="flex-1 space-y-1">
            <a href="/simulator" class="flex items-center gap-3 px-4 py-3 text-secondary hover:bg-slate-100 rounded-lg transition-all font-headline text-sm">
                <span class="material-symbols-outlined">calculate</span><span>価格計算</span>
            </a>
            <a href="/price-chart" class="flex items-center gap-3 px-4 py-3 text-secondary hover:bg-slate-100 rounded-lg transition-all font-headline text-sm">
                <span class="material-symbols-outlined">trending_up</span><span>価格推移</span>
            </a>
            <a href="/customers" class="flex items-center gap-3 px-4 py-3 text-secondary hover:bg-slate-100 rounded-lg transition-all font-headline text-sm">
                <span class="material-symbols-outlined">group</span><span>得意先管理</span>
            </a>
            <a href="/simulator/history" class="flex items-center gap-3 px-4 py-3 sidebar-active font-bold rounded-lg transition-all font-headline text-sm">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">history</span><span>履歴</span>
            </a>
        </nav>
    </aside>

    <main class="ml-64 flex-1 min-h-screen pb-12">
        <header class="sticky top-0 w-full z-30 bg-white/70 backdrop-blur-md flex justify-between items-center px-8 py-6 border-b border-slate-100">
            <div>
                <h2 class="font-extrabold text-navy-dark text-2xl font-headline tracking-tight">価格推移履歴</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">市場価格と分析データの長期トレンド</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="/simulator" class="bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:brightness-110 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">add</span>新規シミュレーション
                </a>
            </div>
        </header>

        <div class="p-8 space-y-8 max-w-[1200px] mx-auto">
            <section class="glass-card p-8 rounded-3xl shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-accent-green rounded-full"></div>
                        <h3 class="font-headline font-extrabold text-lg text-navy-dark">月別平均価格トレンド</h3>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                            <span class="w-3 h-3 rounded-full bg-chart-blue"></span>平均価格 (円/kg)
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                            <span class="w-3 h-3 rounded-full bg-chart-red"></span>ドル円レート
                        </div>
                    </div>
                </div>

                <div class="relative h-[350px]">
                    @if($monthly->count() > 0)
                    <canvas id="monthlyChart"></canvas>
                    @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-300">
                        <span class="material-symbols-outlined text-6xl mb-2">query_stats</span>
                        <p class="text-sm font-medium">データが蓄積されるとグラフが表示されます</p>
                    </div>
                    @endif
                </div>
            </section>

            <section class="space-y-4">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-1.5 h-6 bg-secondary rounded-full"></div>
                    <h3 class="font-headline font-extrabold text-lg text-navy-dark">直近の分析履歴</h3>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">日時</th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">羽毛詳細</th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">産地</th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">単価 (USD)</th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">為替</th>
                                <th class="p-5 text-[10px] font-black text-navy-dark uppercase tracking-widest border-b border-slate-100 text-right">円換算単価</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($histories as $h)
                            <tr class="group hover:bg-slate-50/40 transition-colors">
                                <td class="p-5 text-sm text-slate-500 font-medium">
                                    {{ $h->created_at->format('m/d') }} <span class="text-[10px] opacity-50">{{ $h->created_at->format('H:i') }}</span>
                                </td>
                                <td class="p-5">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter
                                        {{ $h->feather_type === 'ホワイトダック' ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $h->feather_type }}
                                    </span>
                                </td>
                                <td class="p-5 text-sm font-bold text-navy-dark">{{ $h->origin }}</td>
                                <td class="p-5 text-sm font-medium text-slate-600 text-right font-mono">${{ number_format($h->feather_usd, 2) }}</td>
                                <td class="p-5 text-sm font-medium text-slate-600 text-right font-mono">{{ number_format($h->usd_jpy, 2) }}</td>
                                <td class="p-5 text-right">
                                    {{-- 円換算単価：USD単価 × 為替レート をその場で計算 --}}
                                    <span class="text-sm font-black text-navy-dark">
                                        ¥{{ number_format($h->feather_usd * $h->usd_jpy, 1) }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-bold">/kg</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>

    <script>
        @if($monthly->count() > 0)
        const ctx = document.getElementById('monthlyChart').getContext('2d');

        const blueGradient = ctx.createLinearGradient(0, 0, 0, 400);
        blueGradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
        blueGradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($monthly->pluck('month')),
                datasets: [{
                        label: '平均円換算単価',
                        // コントローラーで計算した avg_price を確実に数値として渡す
                        data: @json($monthly->map(fn($m) => (float) $m->avg_price)),
                        borderColor: '#3b82f6',
                        backgroundColor: blueGradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'ドル円レート',
                        data: @json($monthly->map(fn($m) => (float) $m->avg_rate)),
                        borderColor: '#ef4444',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        tension: 0.4,
                        fill: false,
                        yAxisID: 'y2'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1a365d',
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.datasetIndex === 0) {
                                    label += '¥' + Math.round(context.raw).toLocaleString();
                                } else {
                                    label += context.raw.toFixed(2);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        position: 'left',
                        title: {
                            display: true,
                            text: '円/kg',
                            font: {
                                size: 10
                            }
                        },
                        ticks: {
                            callback: (val) => '¥' + val.toLocaleString()
                        }
                    },
                    y2: {
                        position: 'right',
                        title: {
                            display: true,
                            text: 'USD/JPY',
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        @endif
    </script>
</body>

</html>