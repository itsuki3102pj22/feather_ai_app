<!DOCTYPE html>
<html class="light" lang="ja">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Aerostat - 価格推移分析</title>
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
                        "error": "#d32f2f"
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

        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .tab-active {
            color: #1a365d;
            border-bottom: 3px solid #61b05e;
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-slate-900 font-body min-h-screen flex antialiased">
    <aside class="h-screen w-64 fixed left-0 top-0 z-40 bg-[#f8fafc] flex flex-col p-6 gap-2 border-r border-slate-100">
        <div class="mb-10 px-2 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-white">cloud</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-navy-dark tracking-widest font-headline uppercase">AEROSTAT</h1>
                <p class="text-[10px] text-secondary font-bold tracking-tighter uppercase">Feather Management</p>
            </div>
        </div>
        <nav class="flex-1 space-y-1">
            <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 text-secondary hover:bg-slate-100 rounded-lg transition-all font-headline text-sm">
                <span class="material-symbols-outlined">dashboard</span><span>ダッシュボード</span>
            </a>
            <a href="/simulator/form" class="flex items-center gap-3 px-4 py-3 text-secondary hover:bg-slate-100 rounded-lg transition-all font-headline text-sm">
                <span class="material-symbols-outlined">calculate</span><span>価格計算</span>
            </a>
            <a href="/price-chart" class="flex items-center gap-3 px-4 py-3 sidebar-active font-bold rounded-lg transition-all font-headline text-sm">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">trending_up</span><span>価格推移</span>
            </a>
            <a href="/customers" class="flex items-center gap-3 px-4 py-3 text-secondary hover:bg-slate-100 rounded-lg transition-all font-headline text-sm">
                <span class="material-symbols-outlined">group</span><span>得意先管理</span>
            </a>
            <a href="/simulator/history" class="flex items-center gap-3 px-4 py-3 text-secondary hover:bg-slate-100 rounded-lg transition-all font-headline text-sm">
                <span class="material-symbols-outlined">history</span><span>履歴</span>
            </a>
        </nav>
    </aside>

    <main class="ml-64 flex-1 min-h-screen pb-12">
        <header class="sticky top-0 w-full z-30 bg-white/70 backdrop-blur-md flex justify-between items-center px-8 py-5 border-b border-slate-100">
            <h2 class="font-extrabold text-navy-dark text-xl font-headline">羽毛価格推移分析</h2>
            <div class="flex items-center gap-6">
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black text-slate-400 uppercase">Live Exchange Rate</span>
                    <span class="text-lg font-black text-accent-green font-headline" id="rate-display">取得中...</span>
                </div>
            </div>
        </header>

        <div class="p-8 space-y-8 max-w-[1200px] mx-auto">
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3 animate-fade-in">
                <span class="material-symbols-outlined">check_circle</span>{{ session('success') }}
            </div>
            @endif

            <section class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                    <div class="flex gap-8">
                        <button onclick="switchTab('monthly')" id="tab-monthly" class="tab-active pb-4 text-sm font-black transition-all">月次レポート</button>
                        <button onclick="switchTab('weekly')" id="tab-weekly" class="text-slate-400 pb-4 text-sm font-black transition-all hover:text-navy-dark">週次レポート</button>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#ef4444]"></span><span class="text-[10px] font-bold text-slate-500">WD 85%</span></div>
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#3b82f6]"></span><span class="text-[10px] font-bold text-slate-500">GD (WD×70%)</span></div>
                    </div>
                </div>

                <div class="relative h-[400px]">
                    <canvas id="monthlyChart"></canvas>
                    <canvas id="weeklyChart" class="hidden"></canvas>
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <section class="bg-navy-dark text-white p-8 rounded-[2rem] shadow-xl relative overflow-hidden">
                    <span class="material-symbols-outlined absolute -right-4 -top-4 text-[120px] opacity-10">forum</span>
                    <h3 class="font-headline font-extrabold text-lg mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-accent-green">lightbulb</span>市況コメント
                    </h3>

                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 mb-6 min-h-[120px]">
                        @if($latestComment && $latestComment->manual_comment)
                        <p class="text-sm leading-relaxed font-medium italic opacity-90">{{ $latestComment->manual_comment }}</p>
                        @else
                        <p class="text-sm opacity-50 italic">現在、特筆すべきコメントはありません。</p>
                        @endif
                    </div>
                    @if($errors->any())
                    <div style="background:#fde8e8;border:1px solid #f5c6c6;border-radius:6px;padding:12px 16px;margin-bottom:16px;">
                        <ul style="margin:0;padding-left:20px;color:#a94442;">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="/price-chart/comment" method="POST" class="space-y-4">
                        @csrf
                        <textarea name="manual_comment" rows="3" class="w-full bg-white/5 border-white/10 rounded-xl text-sm placeholder:text-white/30 focus:ring-accent-green focus:border-accent-green" placeholder="市場の動き・為替の影響を入力...">{{ $latestComment?->manual_comment }}</textarea>
                        <button type="submit" class="w-full bg-accent-green text-white py-3 rounded-xl text-sm font-bold hover:brightness-110 transition-all">コメントを更新</button>
                    </form>
                </section>

                <section class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                    <h3 class="font-headline font-extrabold text-lg text-navy-dark mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-accent-green">add_chart</span>価格データ登録
                    </h3>
                    <form action="/price-chart/store" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="usd_jpy" id="store_usd_jpy" value="">

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">記録日</label>
                                <input type="date" name="record_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold py-3" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">種別</label>
                                <select name="period_type" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold py-3">
                                    <option value="monthly">月別</option>
                                    <option value="weekly">週別</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-1">WD85% ドル単価 ($/kg)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                                <input type="number" name="white_duck_usd" step="0.01" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold py-3 pl-8" placeholder="0.00" required>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-2xl text-sm font-black shadow-lg shadow-primary/20 hover:translate-y-[-2px] transition-all">
                            データをシステムに記録
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </main>

    <script>
        // 為替取得
        fetch('https://open.er-api.com/v6/latest/USD')
            .then(r => r.json())
            .then(d => {
                const rate = d.rates.JPY;
                document.getElementById('rate-display').textContent = `¥${rate.toFixed(2)}`;
                const input = document.getElementById('store_usd_jpy');
                if (input) input.value = rate.toFixed(2);
            });

        // グラフのインスタンス管理用
        let activeChart = null;

        // データの読み込み
        const rawMonthlyData = @json($monthly);
        const rawWeeklyData = @json($weekly);

        // グラフ描画共通オプション
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 400
            }, // 切り替えをスムーズに
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1a365d',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 12,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 14,
                        weight: 'black'
                    },
                    callbacks: {
                        label: ctx => ` ¥${Number(ctx.raw).toLocaleString()}`
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            weight: '600'
                        },
                        color: '#94a3b8'
                    }
                },
                y: {
                    grid: {
                        color: '#f1f5f9'
                    },
                    ticks: {
                        callback: v => '¥' + (v / 1000) + 'k',
                        color: '#94a3b8'
                    }
                }
            }
        };

        /**
         * グラフを描画するメイン関数
         */
        function updateChart(type) {
            const isMonthly = type === 'monthly';
            const data = isMonthly ? rawMonthlyData : rawWeeklyData;
            const canvasId = isMonthly ? 'monthlyChart' : 'weeklyChart';
            const canvas = document.getElementById(canvasId);

            if (!canvas || !data || data.length === 0) return;

            // --- ここが重要：既存のグラフがあれば完全に破棄する ---
            if (activeChart) {
                activeChart.destroy();
            }

            const ctx = canvas.getContext('2d');
            const labels = data.map(d => {
                // 月別ならYYYY-MM、週別ならそのまま表示
                return isMonthly ? d.record_date.substring(0, 7) : d.record_date;
            });

            activeChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                            label: 'WD85%',
                            data: data.map(d => d.white_duck_jpy),
                            borderColor: '#ef4444',
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 2, // ホバーしやすくするため少しだけ点をつける
                            pointHoverRadius: 6,
                            fill: false
                        },
                        {
                            label: 'GD',
                            data: data.map(d => d.grey_duck_jpy),
                            borderColor: '#3b82f6',
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 2,
                            pointHoverRadius: 6,
                            fill: false
                        }
                    ]
                },
                options: chartOptions
            });
        }

        /**
         * タブ切り替え関数
         */
        function switchTab(type) {
            const isMonthly = type === 'monthly';

            // 1. キャンバスの表示/非表示を即座に切り替え
            document.getElementById('monthlyChart').classList.toggle('hidden', !isMonthly);
            document.getElementById('weeklyChart').classList.toggle('hidden', isMonthly);

            // 2. タブのスタイル切り替え
            document.getElementById('tab-monthly').className = isMonthly ?
                'tab-active pb-4 text-sm font-black transition-all' :
                'text-slate-400 pb-4 text-sm font-black transition-all hover:text-navy-dark';

            document.getElementById('tab-weekly').className = !isMonthly ?
                'tab-active pb-4 text-sm font-black transition-all' :
                'text-slate-400 pb-4 text-sm font-black transition-all hover:text-navy-dark';

            // 3. グラフを再描画（古いグラフを破棄して新しいキャンバスに書く）
            updateChart(type);
        }

        // ページ読み込み時に「月次」を初期表示
        window.onload = () => {
            switchTab('monthly');
        };
    </script>
</body>

</html>