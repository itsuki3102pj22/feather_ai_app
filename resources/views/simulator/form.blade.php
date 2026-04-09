<!DOCTYPE html>

<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Aerostat - 羽毛原料管理システム</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-variant": "#e0e3e5",
                        "on-surface-variant": "#43474e",
                        "inverse-on-surface": "#eef1f3",
                        "error-container": "#ffdad6",
                        "surface-container-highest": "#e0e3e5",
                        "outline": "#74777f",
                        "error": "#ba1a1a",
                        "on-primary-container": "#61b05e",
                        "surface": "#f7fafc",
                        "primary-fixed-dim": "#88d982",
                        "surface-dim": "#d7dadc",
                        "primary-fixed": "#a3f69c",
                        "on-primary": "#ffffff",
                        "secondary-fixed-dim": "#adc7f7",
                        "on-secondary-fixed": "#001b3c",
                        "secondary-fixed": "#d6e3ff",
                        "on-tertiary-fixed": "#111c2c",
                        "on-primary-fixed": "#002204",
                        "on-secondary-fixed-variant": "#2d476f",
                        "on-secondary-container": "#3f5882",
                        "on-background": "#181c1e",
                        "tertiary": "#162132",
                        "primary": "#002705",
                        "on-tertiary-fixed-variant": "#3c475a",
                        "inverse-primary": "#88d982",
                        "surface-container-lowest": "#ffffff",
                        "on-error-container": "#93000a",
                        "tertiary-fixed-dim": "#bcc7dd",
                        "tertiary-container": "#2b3648",
                        "background": "#f7fafc",
                        "inverse-surface": "#2d3133",
                        "surface-container": "#ebeef0",
                        "secondary-container": "#b6d0ff",
                        "tertiary-fixed": "#d8e3fa",
                        "surface-tint": "#1b6d24",
                        "on-tertiary": "#ffffff",
                        "on-surface": "#181c1e",
                        "secondary": "#455f88",
                        "surface-container-low": "#f1f4f6",
                        "on-error": "#ffffff",
                        "surface-container-high": "#e5e9eb",
                        "primary-container": "#00400b",
                        "outline-variant": "#c4c6cf",
                        "surface-bright": "#f7fafc",
                        "on-primary-fixed-variant": "#005312",
                        "on-secondary": "#ffffff",
                        "on-tertiary-container": "#949fb4"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.5rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
        }

        .sidebar-active {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-surface text-on-surface font-body min-h-screen flex">
    <!-- SideNavBar -->
    <aside class="h-screen w-64 fixed left-0 top-0 overflow-y-auto z-40 bg-[#f7fafc] shadow-[8px_0_32px_rgba(22,33,50,0.04)] flex flex-col p-6 gap-2">
        <div class="mb-10 px-2">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#002705] flex items-center justify-center">
                    <span class="material-symbols-outlined text-white" data-icon="cloud">cloud</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-[#1a365d] uppercase tracking-widest font-headline">Aerostat</h1>
                    <p class="text-[10px] text-[#455f88] font-medium tracking-tight">羽毛原料管理システム</p>
                </div>
            </div>
        </div>
        <nav class="flex-1 space-y-1">
            <a class="flex items-center gap-3 px-4 py-3 bg-white text-[#61b05e] font-bold rounded-lg transition-all duration-300 ease-out hover:translate-x-1 sidebar-active font-headline text-sm" href="/simulator">
                <span class="material-symbols-outlined" data-icon="calculate" style="font-variation-settings: 'FILL' 1;">calculate</span>
                <span>価格計算</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-[#455f88] hover:bg-[#ebeef0] rounded-lg transition-all duration-300 ease-out hover:translate-x-1 font-headline text-sm" href="/price-chart">
                <span class="material-symbols-outlined" data-icon="trending_up">trending_up</span>
                <span>価格推移</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-[#455f88] hover:bg-[#ebeef0] rounded-lg transition-all duration-300 ease-out hover:translate-x-1 font-headline text-sm" href="/customers">
                <span class="material-symbols-outlined" data-icon="group">group</span>
                <span>得意先管理</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-[#455f88] hover:bg-[#ebeef0] rounded-lg transition-all duration-300 ease-out hover:translate-x-1 font-headline text-sm" href="/simulator/history">
                <span class="material-symbols-outlined" data-icon="history">history</span>
                <span>履歴</span>
            </a>
        </nav>
    </aside>
    <!-- Main Content -->
    <main class="ml-64 flex-1 min-h-screen">
        <!-- TopAppBar -->
        <header class="sticky top-0 w-full z-30 bg-white/80 backdrop-blur-xl border-b border-[#f7fafc]/10 flex justify-between items-center px-8 py-4">
            <div class="flex items-center gap-8">
                <h2 class="font-black text-[#1a365d] text-lg font-headline">羽毛価格計算</h2>
            </div>
        </header>
        <!-- Canvas Area -->
        <div class="p-12 max-w-6xl mx-auto space-y-12">
            <!-- Rate & Quick Stats Bento -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 glass-panel p-8 rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between overflow-hidden relative">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-[#455f88] tracking-widest uppercase mb-1">Current Exchange Rate</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-black font-headline text-[#1a365d]" id="rate-display">¥151.24</span>
                            <span class="text-[#61b05e] font-bold text-sm flex items-center">
                                <span class="material-symbols-outlined text-xs" data-icon="trending_up">trending_up</span>
                                +0.12%
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-2 font-medium">最終更新: <span id="update-time">2023/10/27 14:30 (JST)</span></p>
                    </div>
                    <div class="hidden lg:block opacity-10 absolute right-[-20px] top-[-20px]">
                        <span class="material-symbols-outlined text-[160px]" data-icon="currency_exchange">currency_exchange</span>
                    </div>
                    <div class="flex gap-4 relative z-10">
                        <div class="text-right">
                            <p class="text-[10px] text-slate-500 uppercase font-bold">USD/JPY</p>
                            <p class="font-headline font-bold text-[#455f88]">Auto-Sync</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-[#d6e3ff] flex items-center justify-center text-[#455f88]">
                            <span class="material-symbols-outlined" data-icon="sync">sync</span>
                        </div>
                    </div>
                </div>
                <div class="bg-[#002705] p-8 rounded-xl text-white shadow-lg flex flex-col justify-between overflow-hidden relative">
                    <div class="absolute right-0 bottom-0 opacity-20 transform translate-x-1/4 translate-y-1/4">
                        <img class="w-40 h-40 object-contain" data-alt="close-up of soft white duck down feathers with delicate texture and ethereal lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCHt8MilTAbPaCUUkPx1HuPLXG_IYuF28016h-CtSeh-jw-RZf5nDUZPbLy0PoUO_tHMtBSf5cthGRQSAfUVDjA11oupdsYWpewSr394BjK1B5ywH4KRaNliMClv4t8ZmsKp3If0YG5wx-WxbW89Rtm_efBQvXY8pZvu8Q9cuCGQYSSibhVvAt2c8HM_t06g_JLpkDG-RtdYvF2YkjYNPh0rb_Q32V4djzPRqkCsEDVKP7XmTqTjT9Qkf4xhgiX1m2zvPGeWiCaj8I" />
                    </div>
                    <div>
                        <p class="text-xs font-bold opacity-70 tracking-widest uppercase mb-1">Global Market Price</p>
                        <h3 class="text-2xl font-black font-headline">Grade-A Down</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-lg font-bold">$95.00</span>
                        <span class="text-xs opacity-60">/ kg</span>
                    </div>
                </div>
            </section>
            <!-- Form section -->
            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-xl shadow-sm mb-8 animate-pulse">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <h3 class="text-sm font-black text-red-800 uppercase tracking-widest">入力内容を確認してください</h3>
                </div>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-sm text-red-700 font-medium flex items-center gap-2">
                        <span class="w-1 h-1 bg-red-400 rounded-full"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="/simulator/analyze" method="POST">
                <!-- @csrf token placeholder -->
                @csrf
                <input id="usd_jpy_input" name="usd_jpy" type="hidden" value="" />
                <section class="grid grid-cols-1 lg:grid-cols-5 gap-12">
                    <!-- Main Form (3/5 width) -->
                    <div class="lg:col-span-3 space-y-12">
                        <!-- 羽毛情報セクション -->
                        <div>
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-1.5 h-8 bg-[#61b05e] rounded-full"></div>
                                <h3 class="text-2xl font-bold font-headline text-[#1a365d]">羽毛情報</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-[#455f88] uppercase tracking-wider ml-1">羽毛の種類</label>
                                    <select class="w-full bg-surface-container-lowest border-none rounded-lg p-4 text-sm focus:ring-2 focus:ring-secondary/20 shadow-sm appearance-none" name="feather_type">
                                        @foreach($featherTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-[#455f88] uppercase tracking-wider ml-1">産地</label>
                                    <select class="w-full bg-surface-container-lowest border-none rounded-lg p-4 text-sm focus:ring-2 focus:ring-secondary/20 shadow-sm" name="origin">
                                        @foreach($origins as $origin)
                                        <option value="{{ $origin }}">{{ $origin }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-xs font-bold text-[#455f88] uppercase tracking-wider ml-1">羽毛原料単価 (USD/kg)</label>
                                    <div class="relative">
                                        <input class="w-full bg-surface-container-lowest border-none rounded-lg p-4 text-sm focus:ring-2 focus:ring-secondary/20 shadow-sm pl-12 font-medium" name="feather_usd" step="0.01" type="number" value="95" />
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                            <span class="material-symbols-outlined text-lg" data-icon="attach_money">attach_money</span>
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 italic mt-1 ml-1">ホワイトダック85%の現地仕入れ価格</p>
                                </div>
                            </div>
                        </div>
                        <!-- 販売設定セクション -->
                        <div>
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-1.5 h-8 bg-[#455f88] rounded-full"></div>
                                <h3 class="text-2xl font-bold font-headline text-[#1a365d]">販売設定</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-[#455f88] uppercase tracking-wider ml-1">自社利益率 (%)</label>
                                    <input class="w-full bg-surface-container-lowest border-none rounded-lg p-4 text-sm focus:ring-2 focus:ring-secondary/20 shadow-sm font-medium" name="profit_rate" type="number" value="10" />
                                    <p class="text-[11px] text-slate-500 italic mt-1 ml-1">PDF見積書には表示されません</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-[#455f88] uppercase tracking-wider ml-1">得意先名</label>
                                    <input class="w-full bg-surface-container-lowest border-none rounded-lg p-4 text-sm focus:ring-2 focus:ring-secondary/20 shadow-sm" name="customer_name" placeholder="例：○○寝具株式会社" type="text" />
                                </div>
                            </div>
                        </div>
                        <!-- Action Area -->
                        <div class="pt-6 flex justify-end">
                            <button class="px-12 py-5 bg-gradient-to-r from-[#61b05e] to-[#002705] text-white font-black rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 active:scale-95 text-lg font-headline flex items-center gap-3" type="submit">
                                <span class="material-symbols-outlined" data-icon="calculate" style="font-variation-settings: 'FILL' 1;">calculate</span>
                                計算する
                            </button>
                        </div>
                    </div>
                    <!-- Sidebar Summary/Guide (2/5 width) -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-[#ebeef0] p-8 rounded-xl border border-white/50">
                            <h4 class="text-sm font-bold text-[#162132] mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm" data-icon="info">info</span>
                                計算モデルの概要
                            </h4>
                            <div class="space-y-6">
                                <div class="flex gap-4">
                                    <div class="w-8 h-8 rounded bg-white flex items-center justify-center shrink-0">
                                        <span class="text-xs font-bold text-[#455f88]">01</span>
                                    </div>
                                    <p class="text-xs text-[#455f88] leading-relaxed">
                                        入力されたUSD単価に為替レートを乗算し、基本の日本円仕入れ価格を算出します。
                                    </p>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-8 h-8 rounded bg-white flex items-center justify-center shrink-0">
                                        <span class="text-xs font-bold text-[#455f88]">02</span>
                                    </div>
                                    <p class="text-xs text-[#455f88] leading-relaxed">
                                        関税、輸送費、および弊社の標準諸経費が自動的に加算されます。
                                    </p>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-8 h-8 rounded bg-white flex items-center justify-center shrink-0">
                                        <span class="text-xs font-bold text-[#455f88]">03</span>
                                    </div>
                                    <p class="text-xs text-[#455f88] leading-relaxed">
                                        指定された自社利益率を適用し、最終的な得意先提示価格を提示します。
                                    </p>
                                </div>
                            </div>
                            <div class="mt-8 pt-8 border-t border-slate-300/30">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-xs font-bold text-slate-500 uppercase">Quality Grade</span>
                                    <span class="px-3 py-1 bg-[#adc7f7] text-[#001b3c] text-[10px] font-bold rounded-full">PREMIUM SOURCED</span>
                                </div>
                                <div class="w-full h-1 bg-slate-300 rounded-full overflow-hidden">
                                    <div class="w-4/5 h-full bg-[#61b05e]"></div>
                                </div>
                            </div>
                        </div>
                        <div class="relative rounded-xl overflow-hidden group">
                            <img class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500" data-alt="high-quality macro photography of premium white goose down clusters on a dark slate background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDriti6byLOF3nq3fSkzdchED0448R9hBw0n6sYtX0YZd8zCAMJhzZ19gRej7SJ6nAjrvRCCrOWM6-4drELKSrr3GPpoPFenlVzxl8Dglyo7IQ1mPtwJ-xYzvaamOqQQ4j4hhDXbMBg_esTE_UaEjSpaYbp9rctHxT3smMDnQu0q0vCOHBrrSgOjwwYnnzY6agNoCTvgWOv8GHZF122B3Vy7l4kpx5T-6BNzRKaoqbGVQf_xg_MX6SIZjSk-HpO6YwGzWu37JtAEWc" />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#002705]/80 to-transparent flex items-end p-6">
                                <p class="text-white text-xs font-medium leading-tight">
                                    品質データに基づいた<br /><span class="text-lg font-bold">市場予測レポート</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </main>
    <script>
        fetch('https://open.er-api.com/v6/latest/USD')
            .then(response => response.json())
            .then(data => {
                const jpyRate = data.rates.JPY;
                document.getElementById('rate-display').innerText = `¥${jpyRate.toFixed(2)}`;
                document.getElementById('usd_jpy_input').value = jpyRate;
                const now = new Date();
                const timestamp = `${now.getFullYear()}/${(now.getMonth()+1).toString().padStart(2, '0')}/${now.getDate().toString().padStart(2, '0')} ${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')} (JST)`;
                document.getElementById('update-time').innerText = timestamp;
            })
            .catch(error => console.error('Error fetching exchange rate:', error));
    </script>
</body>

</html>