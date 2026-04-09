<!DOCTYPE html>
<html class="light" lang="ja">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Aerostat - 価格一覧</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@300;500;700;800&family=Material+Symbols+Outlined:wght@100..700&display=swap" rel="stylesheet">
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
        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
        }

        .table-container::-webkit-scrollbar {
            height: 6px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .bento-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .bento-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-slate-900 font-body min-h-screen flex antialiased">
    <aside class="h-screen w-64 fixed left-0 top-0 overflow-y-auto z-40 bg-[#f8fafc] flex flex-col p-6 gap-2 border-r border-slate-100">
        <div class="mb-10 px-2">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">cloud</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-navy-dark tracking-widest font-headline">AEROSTAT</h1>
                    <p class="text-[10px] text-secondary font-medium uppercase">羽毛原料管理システム</p>
                </div>
            </div>
        </div>
        <nav class="flex-1 space-y-1">
            <a class="flex items-center gap-3 px-4 py-3 bg-white text-accent-green font-bold rounded-lg shadow-sm border border-slate-100" href="/simulator/form">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">calculate</span>
                <span>価格計算</span>
            </a>
        </nav>
    </aside>

    <main class="ml-64 flex-1 min-h-screen pb-12">
        <header class="sticky top-0 w-full z-30 bg-white/70 backdrop-blur-md flex justify-between items-center px-8 py-4 border-b border-slate-100">
            <h2 class="font-extrabold text-navy-dark text-xl font-headline">シミュレーション結果</h2>
            <div class="flex items-center gap-4">
                <a href="/simulator/form" class="text-sm font-bold text-secondary hover:text-navy-dark transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>条件入力に戻る
                </a>
            </div>
        </header>

        <div class="p-8 space-y-8 max-w-[1400px] mx-auto">
            <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bento-card bg-white p-5 rounded-2xl border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 flex items-center justify-center rounded-xl text-indigo-500">
                        <span class="material-symbols-outlined">category</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">羽毛種</p>
                        <p class="text-base font-bold text-navy-dark">{{ $simulation->feather_type }}</p>
                    </div>
                </div>
                <div class="bento-card bg-white p-5 rounded-2xl border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 flex items-center justify-center rounded-xl text-emerald-500">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">ドル円レート</p>
                        <p class="text-base font-bold text-accent-green">¥{{ number_format($simulation->usd_jpy, 2) }}</p>
                    </div>
                </div>
                <div class="bento-card bg-white p-5 rounded-2xl border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 flex items-center justify-center rounded-xl text-blue-500">
                        <span class="material-symbols-outlined">attach_money</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">ドル単価</p>
                        <p class="text-base font-bold text-navy-dark">${{ number_format($simulation->feather_usd, 2) }}/kg</p>
                    </div>
                </div>
                <div class="bento-card bg-white p-5 rounded-2xl border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-50 flex items-center justify-center rounded-xl text-slate-400">
                        <span class="material-symbols-outlined">corporate_fare</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">得意先</p>
                        <p class="text-base font-bold text-navy-dark truncate max-w-[120px]">{{ $simulation->customer_name ?: '未設定' }}</p>
                    </div>
                </div>
            </section>

            <section class="flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-2">
                    <button class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 rounded-lg transition-all" onclick="selectAll()">一括選択</button>
                    <button class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 rounded-lg transition-all" onclick="clearAll()">解除</button>
                    <div class="h-4 w-px bg-slate-200 mx-2"></div>
                    <span class="text-xs font-bold text-slate-400" id="selectedCount">0 件選択中</span>
                </div>
                <button class="bg-error text-white px-6 py-2.5 rounded-xl text-sm font-extrabold shadow-lg shadow-error/20 hover:brightness-110 disabled:opacity-30 transition-all flex items-center gap-2" id="pdfBtn" onclick="submitMultiplePdf()" disabled>
                    <span class="material-symbols-outlined text-lg">picture_as_pdf</span>一括PDF出力
                </button>
            </section>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="table-container overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="priceTable">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="p-5 w-16 text-center border-b border-slate-100">
                                    <input type="checkbox" id="checkAll" onchange="toggleAll(this.checked)" class="rounded border-slate-300 text-accent-green focus:ring-accent-green/20">
                                </th>
                                <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">産地</th>
                                @foreach($downRatios as $ratio)
                                <th class="p-5 text-[10px] font-black text-center uppercase tracking-widest border-b {{ $ratio == 85 ? 'text-navy-dark border-b-2 border-navy-dark bg-slate-50/80' : 'text-slate-400 border-slate-100' }}">
                                    {{ $ratio }}%
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($priceTable as $rowIndex => $row)
                            <tr class="group hover:bg-slate-50/40 transition-colors" id="row-{{ $rowIndex }}">
                                <td class="p-5 text-center">
                                    <input type="checkbox" class="row-check rounded border-slate-300 text-accent-green" data-index="{{ $rowIndex }}" onchange="onCheckChange()">
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2 h-2 rounded-full bg-accent-green shadow-sm"></span>
                                        <span class="text-sm font-bold text-navy-dark">{{ $row['origin'] }}</span>
                                    </div>
                                </td>
                                @foreach($downRatios as $ratio)
                                <td class="p-5 text-sm text-center font-medium cursor-pointer hover:text-accent-green transition-colors {{ $ratio == 85 ? 'font-extrabold text-navy-dark bg-slate-50/40' : 'text-slate-600' }}"
                                    onclick="openModal({{ json_encode($row) }}, {{ $simulation->profit_rate }}, '{{ $simulation->feather_type }}', {{ $simulation->usd_jpy }}, {{ $simulation->feather_usd }}, '{{ $simulation->customer_name }}', {{ $ratio }})">
                                    ¥{{ number_format($row['prices'][$ratio]['sell']) }}
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-6 hidden" id="detailModal">
        <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden border border-slate-100">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-headline font-extrabold text-lg text-navy-dark">販売価格詳細</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase mb-1">産地</p>
                        <p class="font-bold" id="m-origin"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase mb-1">ダウン比率</p>
                        <p class="font-bold" id="m-ratio"></p>
                    </div>
                    <div class="col-span-2 bg-slate-50 p-4 rounded-xl">
                        <p class="text-[10px] text-slate-400 font-black uppercase mb-1">現在の販売価格</p>
                        <p class="text-2xl font-black text-accent-green" id="m-sell-display"></p>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">利益率調整 (%)</label>
                    <input type="number" id="m-profit" class="w-full bg-slate-50 border-none rounded-xl p-4 text-base font-bold focus:ring-2 focus:ring-accent-green/20" oninput="recalcModal()">
                </div>
                <button class="w-full bg-error text-white py-4 rounded-2xl text-base font-extrabold shadow-xl shadow-error/10 hover:brightness-110 transition-all flex items-center justify-center gap-2" onclick="submitSinglePdf()">
                    <span class="material-symbols-outlined">picture_as_pdf</span>PDFを出力する
                </button>
            </div>
        </div>
    </div>

    <form id="pdfMultipleForm" action="/simulator/pdf-multiple" method="POST" style="display:none;">@csrf<input type="hidden" name="items" id="pdf-items"><input type="hidden" name="customer_name" value="{{ $simulation->customer_name }}"></form>
    <form id="pdfForm" action="/simulator/pdf" method="POST" style="display:none;">@csrf<input type="hidden" name="usd_jpy" id="pdf-rate"><input type="hidden" name="feather_usd" id="pdf-fusd"><input type="hidden" name="feather_type" id="pdf-ftype"><input type="hidden" name="origin" id="pdf-origin"><input type="hidden" name="down_ratio" id="pdf-ratio"><input type="hidden" name="sell_price_jpy" id="pdf-sell"><input type="hidden" name="customer_name" id="pdf-customer"></form>

    <script>
        // ロジック部分は元のJavaScriptをベースに、UIの更新処理を微調整
        const priceTableData = @json($priceTable);
        let currentRow = null;
        let currentRatio = 85;

        function updateCount() {
            const count = document.querySelectorAll('.row-check:checked').length;
            document.getElementById('selectedCount').innerText = `${count} 件選択中`;
            document.getElementById('pdfBtn').disabled = (count === 0);
        }

        function toggleAll(checked) {
            document.querySelectorAll('.row-check').forEach(cb => {
                cb.checked = checked;
                cb.closest('tr').classList.toggle('bg-slate-50/60', checked);
            });
            updateCount();
        }

        function onCheckChange() {
            updateCount();
        }

        function openModal(row, profit, type, rate, fusd, customer, ratio) {
            currentRow = row;
            currentRatio = ratio;
            document.getElementById('m-origin').innerText = row.origin;
            document.getElementById('m-ratio').innerText = ratio + '%';
            document.getElementById('m-profit').value = profit;

            // モーダル表示用の値をセット
            document.getElementById('pdf-rate').value = rate;
            document.getElementById('pdf-fusd').value = fusd;
            document.getElementById('pdf-ftype').value = type;
            document.getElementById('pdf-customer').value = customer;

            recalcModal();
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        function recalcModal() {
            const profit = parseFloat(document.getElementById('m-profit').value) || 0;
            const cost = currentRow.prices[currentRatio].cost;
            const newSell = Math.round(cost / (1 - profit / 100));
            document.getElementById('m-sell-display').innerText = '¥' + newSell.toLocaleString();

            // 送信用隠しフィールド更新
            document.getElementById('pdf-origin').value = currentRow.origin;
            document.getElementById('pdf-ratio').value = currentRatio;
            document.getElementById('pdf-sell').value = newSell;
        }

        function submitSinglePdf() {
            document.getElementById('pdfForm').submit();
        }

        function submitMultiplePdf() {
            const selected = [];
            document.querySelectorAll('.row-check:checked').forEach(cb => {
                const row = priceTableData[cb.dataset.index];
                const featherType = document.getElementById('pdf-ftype').value || '{{ $featherType }}';
                const defaultRatio = 85;
                const sellPrice = row.prices[defaultRatio]?.sell || 0;
                
                selected.push({
                    feather_type: featherType,
                    origin: row.origin,
                    down_ratio: defaultRatio,
                    sell_price: sellPrice
                });
            });
            document.getElementById('pdf-items').value = JSON.stringify(selected);
            document.getElementById('pdfMultipleForm').submit();
        }
    </script>
</body>

</html>