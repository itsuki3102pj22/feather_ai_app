<!DOCTYPE html>
<html class="light" lang="ja">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Aerostat - 得意先・契約管理</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .modal-show {
            display: flex !important;
            animation: fade-in 0.2s ease-out;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: scale(0.98);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
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
            <a href="/customers" class="flex items-center gap-3 px-4 py-3 sidebar-active font-bold rounded-lg transition-all font-headline text-sm">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group</span><span>得意先管理</span>
            </a>
            <a href="/simulator/history" class="flex items-center gap-3 px-4 py-3 text-secondary hover:bg-slate-100 rounded-lg transition-all font-headline text-sm">
                <span class="material-symbols-outlined">history</span><span>履歴</span>
            </a>
        </nav>
    </aside>

    <main class="ml-64 flex-1 min-h-screen pb-12">
        <header class="sticky top-0 w-full z-30 bg-white/70 backdrop-blur-md flex justify-between items-center px-8 py-6 border-b border-slate-100">
            <div>
                <h2 class="font-extrabold text-navy-dark text-2xl font-headline tracking-tight">得意先・契約管理</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">取引状況とデリバリー進捗のリアルタイム監視</p>
            </div>
            <button onclick="toggleModal('newCustomerModal')" class="bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:brightness-110 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">person_add</span>新規取引先追加
            </button>
        </header>

        <div class="p-8 space-y-8 max-w-[1400px] mx-auto">
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3 animate-fade-in">
                <span class="material-symbols-outlined">check_circle</span>{{ session('success') }}
            </div>
            @endif

            <section class="glass-card p-6 rounded-3xl shadow-sm flex items-center justify-between gap-6">
                <div class="flex items-center gap-4 flex-1">
                    <span class="material-symbols-outlined text-slate-400">search</span>
                    <form method="GET" action="/customers" id="selectForm" class="flex-1">
                        <select name="customer_id" onchange="document.getElementById('selectForm').submit()"
                            class="w-full max-w-md bg-transparent border-none text-lg font-bold text-navy-dark focus:ring-0 cursor-pointer">
                            <option value="">― 取引先を選択してください ―</option>
                            @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ optional($selectedCustomer)->id == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                @if($selectedCustomer)
                <div class="flex items-center gap-4 px-6 border-l border-slate-100">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Company Contact</p>
                        <p class="text-sm font-bold text-navy-dark">{{ $selectedCustomer->contact ?: '担当未設定' }}</p>
                    </div>
                    <button onclick="toggleModal('newContractModal')" class="bg-accent-green text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:brightness-105 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">add_box</span>新規契約
                    </button>
                </div>
                @endif
            </section>

            @if($selectedCustomer)
            <section class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="font-headline font-extrabold text-lg text-navy-dark">契約デリバリー状況</h3>
                </div>
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">シーズン</th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">詳細情報</th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">契約 (kg)</th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">出荷済 (kg)</th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">在庫残 (kg)</th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">進捗率</th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">出荷アクション</th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($contracts as $c)
                            <tr class="group hover:bg-slate-50/40 transition-colors">
                                <td class="p-5 text-sm font-black text-secondary">{{ $c->season }}</td>
                                <td class="p-5">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs font-bold text-navy-dark">{{ $c->origin }} / {{ $c->down_ratio }}%</span>
                                        <span class="inline-flex w-fit px-2 py-0.5 rounded text-[9px] font-black uppercase
                                                {{ str_contains($c->feather_type, 'ホワイト') ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $c->feather_type }}
                                        </span>
                                        @if($c->comment)
                                        <span class="text-[11px] text-slate-500">{{ mb_strimwidth($c->comment, 0, 50, '...') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-5 text-sm font-bold text-right font-mono">{{ number_format($c->contract_kg, 1) }}</td>
                                <td class="p-5 text-sm font-bold text-right font-mono text-slate-400">{{ number_format($c->shipped_kg, 1) }}</td>
                                <td class="p-5 text-right font-mono">
                                    <span class="text-sm font-black {{ $c->remaining_kg <= 0 ? 'text-accent-green' : 'text-error' }}">
                                        {{ number_format($c->remaining_kg, 1) }}
                                    </span>
                                </td>
                                <td class="p-5 w-40">
                                    <div class="space-y-1.5">
                                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-accent-green h-full rounded-full transition-all duration-1000" style="width: {{ min($c->progress_rate, 100) }}%"></div>
                                        </div>
                                        <div class="flex justify-between items-center text-[10px] font-black">
                                            <span class="text-accent-green">{{ $c->progress_rate }}%</span>
                                            <span class="text-slate-300 tracking-tighter">DELIVERED</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5">
                                    @if($c->remaining_kg > 0)
                                    <form method="POST" action="/customers/ship" class="flex items-center justify-end gap-2">
                                        @csrf
                                        <input type="hidden" name="contract_id" value="{{ $c->id }}">
                                        <input type="hidden" name="customer_id" value="{{ $selectedCustomer->id }}">
                                        <input type="number" name="add_kg" step="0.1" min="0.1" max="{{ $c->remaining_kg }}" required
                                            class="ship-quantity-input w-20 px-3 py-1.5 text-xs font-bold border-slate-200 rounded-lg focus:ring-accent-green" placeholder="数量">
                                        <button type="submit" class="ship-submit-button bg-navy-dark text-white p-1.5 rounded-lg hover:bg-black transition-all disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                            <span class="material-symbols-outlined text-sm">local_shipping</span>
                                        </button>
                                    </form>
                                    @else
                                    <div class="flex justify-end items-center gap-1 text-accent-green">
                                        <span class="material-symbols-outlined text-sm">task_alt</span>
                                        <span class="text-[10px] font-black uppercase">Completed</span>
                                    </div>
                                    @endif
                                </td>
                                <td class="p-5 text-center">
                                    <form method="POST" action="/customers/contract/destroy" onsubmit="return confirm('契約を削除しますか？')">
                                        @csrf
                                        <input type="hidden" name="contract_id" value="{{ $c->id }}">
                                        <input type="hidden" name="customer_id" value="{{ $selectedCustomer->id }}">
                                        <button type="submit" class="text-slate-300 hover:text-error transition-colors">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif
        </div>
    </main>

    <div id="newCustomerModal" class="hidden fixed inset-0 z-50 bg-navy-dark/60 backdrop-blur-sm items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-10 relative overflow-hidden">
            <h2 class="text-2xl font-black text-navy-dark font-headline mb-2">新規取引先登録</h2>
            <p class="text-xs text-slate-400 font-bold mb-6 uppercase tracking-widest">Add New Enterprise</p>

            @if($errors->has('name'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-error text-error text-xs font-bold rounded-xl animate-pulse">
                <ul class="list-none p-0 m-0">
                    @foreach($errors->get('name') as $msg) <li>{{ $msg }}</li> @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="/customers" class="space-y-5">
                @csrf
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">会社名 *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold py-4 focus:ring-2 focus:ring-primary {{ $errors->has('name') ? 'ring-2 ring-error' : '' }}"
                        placeholder="例：○○寝具株式会社">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-1">担当者</label>
                        <input type="text" name="contact" value="{{ old('contact') }}" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold py-4 focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-1">電話番号</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold py-4 focus:ring-2 focus:ring-primary">
                    </div>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="toggleModal('newCustomerModal')" class="flex-1 bg-slate-100 text-slate-500 py-4 rounded-2xl text-sm font-black hover:bg-slate-200 transition-all">キャンセル</button>
                    <button type="submit" class="flex-1 bg-primary text-white py-4 rounded-2xl text-sm font-black shadow-lg shadow-primary/20 transition-all">登録実行</button>
                </div>
            </form>
        </div>
    </div>

    <div id="newContractModal" class="hidden fixed inset-0 z-50 bg-navy-dark/60 backdrop-blur-sm items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl p-10 relative overflow-hidden">
            <h2 class="text-2xl font-black text-navy-dark font-headline mb-2">新規契約登録</h2>
            <p class="text-xs text-slate-400 font-bold mb-6 uppercase tracking-widest">Create New Contract</p>

            @if($errors->has('contract_kg') || $errors->has('season') || $errors->has('feather_type') || $errors->has('origin') || $errors->has('down_ratio'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-error text-error text-xs font-bold rounded-xl animate-pulse">
                契約内容を確認してください（数量は1.0kg以上が必要です）。
            </div>
            @endif

            <form method="POST" action="/customers/contract" class="space-y-4">
                @csrf
                <input type="hidden" name="customer_id" value="{{ optional($selectedCustomer)->id }}">

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-1">シーズン</label>
                        <input type="text" name="season" value="{{ old('season', date('Y') . '-' . (date('Y')+1)) }}" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold py-3">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-1">羽毛種</label>
                        <select name="feather_type" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold py-3">
                            @foreach(['ホワイトダック', 'ホワイトグース', 'グレーダック', 'グレーグース'] as $type)
                            <option value="{{ $type }}" {{ old('feather_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-1">産地</label>
                        <select name="origin" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold py-3">
                            @foreach(['中国', 'フランス', 'ロシア', 'イタリア', 'ウクライナ', 'ポーランド'] as $origin)
                            <option value="{{ $origin }}" {{ old('origin') == $origin ? 'selected' : '' }}>{{ $origin }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-1">ダウン比率</label>
                        <select name="down_ratio" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold py-3">
                            @foreach([85, 90, 93, 95, 70, 75, 80] as $ratio)
                            <option value="{{ $ratio }}" {{ old('down_ratio', 85) == $ratio ? 'selected' : '' }}>{{ $ratio }}%</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-1">契約数量 (kg)</label>
                        <input type="number" name="contract_kg" value="{{ old('contract_kg') }}" step="0.1"
                            class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold py-3 {{ $errors->has('contract_kg') ? 'ring-2 ring-error' : '' }}"
                            placeholder="500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-1">契約単価 (円/kg)</label>
                        <input type="number" name="unit_price_jpy" value="{{ old('unit_price_jpy') }}" step="1" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold py-3" placeholder="3500">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-1">コメント</label>
                    <textarea name="comment" rows="3" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold py-3 px-4 resize-none" placeholder="備考や契約メモを入力できます">{{ old('comment') }}</textarea>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="button" onclick="toggleModal('newContractModal')" class="flex-1 bg-slate-100 text-slate-500 py-4 rounded-2xl text-sm font-black">閉じる</button>
                    <button type="submit" class="flex-1 bg-accent-green text-white py-4 rounded-2xl text-sm font-black shadow-lg shadow-accent-green/20">契約を確定</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('modal-show');
            } else {
                modal.classList.remove('modal-show');
                modal.classList.add('hidden');
            }
        }

        function updateShipButtonState(input) {
            const button = input.closest('form')?.querySelector('.ship-submit-button');
            if (!button) return;
            const value = parseFloat(input.value);
            button.disabled = !(value > 0);
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.ship-quantity-input').forEach((input) => {
                updateShipButtonState(input);
                input.addEventListener('input', () => updateShipButtonState(input));
            });
        });

        // ページ読み込み時にバリデーションエラーがあれば、該当するモーダルを自動展開
        window.onload = () => {
            @if($errors->any())
                @if($errors->has('name'))
                    toggleModal('newCustomerModal');
                @else
                    toggleModal('newContractModal');
                @endif
            @endif
        };
    </script>
</body>
</html>