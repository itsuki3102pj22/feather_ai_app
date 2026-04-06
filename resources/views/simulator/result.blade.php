<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>価格一覧 - 羽毛価格計算</title>
    <style>
        body { font-family: sans-serif; max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #2c3e50; border-bottom: 3px solid #27ae60; padding-bottom: 10px; }
        nav { margin-bottom: 24px; }
        nav a { margin-right: 16px; color: #2980b9; text-decoration: none; font-weight: bold; }
        .info-bar {
            background: #f8f9fa; border-radius: 8px; padding: 14px 20px;
            margin-bottom: 16px; display: flex; gap: 24px; flex-wrap: wrap; font-size: 14px;
        }
        .info-bar span { color: #666; }
        .info-bar strong { color: #2c3e50; }
        .action-bar {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 12px; flex-wrap: wrap;
        }
        .btn { padding: 9px 18px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-green  { background: #27ae60; color: white; }
        .btn-blue   { background: #2980b9; color: white; }
        .btn-red    { background: #e74c3c; color: white; }
        .btn-gray   { background: #95a5a6; color: white; }
        .btn:disabled { background: #ccc; cursor: not-allowed; }
        .selected-count { font-size: 13px; color: #666; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th {
            background: #2c3e50; color: white; padding: 10px 8px;
            text-align: center; white-space: nowrap;
        }
        th.origin-col { text-align: left; min-width: 80px; }
        th.check-col  { width: 36px; }
        td { padding: 8px; border-bottom: 1px solid #e0e0e0; text-align: right; }
        td.origin-cell { text-align: left; font-weight: bold; color: #2c3e50; }
        td.check-cell  { text-align: center; }
        tr.selected td { background: #eaf4fb; }
        tr:hover td { background: #f0f7ff; }
        .sell-price { color: #1a5276; font-weight: bold; }
        .highlight-col { background: #fff8e1; }
        th.highlight-col { background: #f39c12; }
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100; }
        .modal { background: white; border-radius: 8px; padding: 28px; width: 420px; margin: 80px auto; }
        .modal h2 { margin-top: 0; color: #2c3e50; }
        .modal-table td { text-align: left; padding: 6px 8px; border-bottom: 1px solid #eee; }
        .modal-table .label { color: #666; width: 120px; }
        .modal-table .value { font-weight: bold; }
        label.field-label { display: block; font-size: 13px; font-weight: bold; margin: 10px 0 4px; color: #555; }
        input[type=number] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box; }
    </style>
</head>
<body>
    <nav>
        <a href="/simulator">価格計算</a>
        <a href="/price-chart">価格推移</a>
        <a href="/customers">得意先管理</a>
        <a href="/simulator/history">履歴</a>
    </nav>

    <h1>価格一覧</h1>

    <div class="info-bar">
        <div><span>羽毛種：</span><strong>{{ $featherType }}</strong></div>
        <div><span>ドル円レート：</span><strong>{{ number_format($usdJpy, 2) }}円</strong></div>
        <div><span>ドル単価：</span><strong>${{ number_format($featherUsd, 2) }}/kg</strong></div>
        <div><span>得意先：</span><strong>{{ $customerName ?: '未設定' }}</strong></div>
    </div>

    <div class="action-bar">
        <button class="btn btn-blue" onclick="selectAll()">全て選択</button>
        <button class="btn btn-gray" onclick="clearAll()">選択解除</button>
        <button class="btn btn-red" id="pdfBtn" onclick="submitMultiplePdf()" disabled>
            チェックした行をPDF出力
        </button>
        <span class="selected-count" id="selectedCount">0件選択中</span>
    </div>

    <table id="priceTable">
        <thead>
            <tr>
                <th class="check-col">
                    <input type="checkbox" id="checkAll" onchange="toggleAll(this.checked)"
                           style="cursor:pointer;">
                </th>
                <th class="origin-col">産地</th>
                @foreach($downRatios as $ratio)
                <th class="{{ $ratio == 85 ? 'highlight-col' : '' }}">{{ $ratio }}%</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($priceTable as $rowIndex => $row)
            <tr id="row-{{ $rowIndex }}" onclick="toggleRow({{ $rowIndex }})">
                <td class="check-cell" onclick="event.stopPropagation()">
                    <input type="checkbox" class="row-check" data-index="{{ $rowIndex }}"
                           onchange="onCheckChange()" style="cursor:pointer;">
                </td>
                <td class="origin-cell">{{ $row['origin'] }}</td>
                @foreach($downRatios as $ratio)
                <td class="sell-price {{ $ratio == 85 ? 'highlight-col' : '' }}"
                    onclick="event.stopPropagation(); openModal({{ json_encode($row) }}, {{ $profitRate }}, '{{ $featherType }}', {{ $usdJpy }}, {{ $featherUsd }}, '{{ $customerName }}', {{ $ratio }})">
                    ¥{{ number_format($row['prices'][$ratio]['sell']) }}
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 24px;">
        <a href="/simulator" class="btn btn-gray">← 再計算</a>
    </div>

    <!-- 詳細・利益率調整モーダル -->
    <div class="modal-overlay" id="detailModal" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal">
            <h2 id="modal-title">価格詳細</h2>
            <table class="modal-table">
                <tr><td class="label">産地</td><td class="value" id="m-origin"></td></tr>
                <tr><td class="label">羽毛種</td><td class="value" id="m-type"></td></tr>
                <tr><td class="label">ダウン比率</td><td class="value" id="m-ratio"></td></tr>
                <tr><td class="label">ドル円レート</td><td class="value" id="m-rate"></td></tr>
                <tr><td class="label">単価</td><td class="value" id="m-cost"></td></tr>
                <tr><td class="label">販売単価</td><td class="value" id="m-sell" style="color:#1a5276;font-size:16px;"></td></tr>
            </table>
            <label class="field-label">利益率を調整（%）</label>
            <input type="number" id="m-profit" value="10" step="0.5" min="0" max="100" oninput="recalcModal()">
            <div style="margin-top:16px;display:flex;gap:8px;">
                <button class="btn btn-red" onclick="submitSinglePdf()" style="flex:1;">PDF出力</button>
                <button class="btn btn-gray" onclick="document.getElementById('detailModal').style.display='none'" style="flex:1;">閉じる</button>
            </div>
        </div>
    </div>

    <!-- 一括PDF送信フォーム -->
    <form id="pdfMultipleForm" action="/simulator/pdf-multiple" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="items" id="pdf-items">
        <input type="hidden" name="customer_name" value="{{ $customerName }}">
    </form>

    <!-- 単体PDF送信フォーム -->
    <form id="pdfForm" action="/simulator/pdf" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="usd_jpy" id="pdf-rate">
        <input type="hidden" name="feather_usd" id="pdf-fusd">
        <input type="hidden" name="feather_type" id="pdf-ftype">
        <input type="hidden" name="origin" id="pdf-origin">
        <input type="hidden" name="down_ratio" id="pdf-ratio">
        <input type="hidden" name="sell_price_jpy" id="pdf-sell">
        <input type="hidden" name="customer_name" id="pdf-customer">
    </form>

    <script>
        const priceTable     = @json($priceTable);
        const downRatios     = @json($downRatios);
        const globalType     = '{{ $featherType }}';
        const globalUsdJpy   = {{ $usdJpy }};
        const globalFusd     = {{ $featherUsd }};
        const globalCustomer = '{{ $customerName }}';
        const defaultProfit  = {{ $profitRate }};

        let currentRow   = null;
        let currentRatio = 85;

        // チェックボックス関連
        function toggleAll(checked) {
            document.querySelectorAll('.row-check').forEach(cb => {
                cb.checked = checked;
                const tr = document.getElementById('row-' + cb.dataset.index);
                tr.classList.toggle('selected', checked);
            });
            updateCount();
        }

        function toggleRow(index) {
            const cb = document.querySelector('.row-check[data-index="' + index + '"]');
            cb.checked = !cb.checked;
            document.getElementById('row-' + index).classList.toggle('selected', cb.checked);
            updateCount();
        }

        function onCheckChange() {
            const all   = document.querySelectorAll('.row-check');
            const checked = document.querySelectorAll('.row-check:checked');
            document.getElementById('checkAll').checked = all.length === checked.length;
            all.forEach(cb => {
                document.getElementById('row-' + cb.dataset.index)
                    .classList.toggle('selected', cb.checked);
            });
            updateCount();
        }

        function updateCount() {
            const count = document.querySelectorAll('.row-check:checked').length;
            document.getElementById('selectedCount').textContent = count + '件選択中';
            document.getElementById('pdfBtn').disabled = count === 0;
        }

        function selectAll() {
            document.getElementById('checkAll').checked = true;
            toggleAll(true);
        }

        function clearAll() {
            document.getElementById('checkAll').checked = false;
            toggleAll(false);
        }

        // 一括PDF出力（85%の販売単価で出力）
        function submitMultiplePdf() {
            const checked = document.querySelectorAll('.row-check:checked');
            const items   = [];
            checked.forEach(cb => {
                const row = priceTable[cb.dataset.index];
                const cost = row.prices[85].cost;
                const sell = Math.round(cost * (1 + defaultProfit / 100));
                items.push({
                    feather_type: globalType,
                    origin:       row.origin,
                    down_ratio:   85,
                    sell_price:   sell,
                });
            });
            document.getElementById('pdf-items').value = JSON.stringify(items);
            document.getElementById('pdfMultipleForm').submit();
        }

        // モーダル開く
        function openModal(row, profitRate, ftype, usdJpy, featherUsd, customer, ratio) {
            currentRow   = row;
            currentRatio = ratio || 85;

            document.getElementById('m-profit').value          = profitRate;
            document.getElementById('m-origin').textContent    = row.origin;
            document.getElementById('m-type').textContent      = ftype;
            document.getElementById('m-ratio').textContent     = currentRatio + '%';
            document.getElementById('m-rate').textContent      = usdJpy.toFixed(2) + ' 円';
            document.getElementById('modal-title').textContent = row.origin + ' / ' + currentRatio + '%';

            recalcModal();
            document.getElementById('detailModal').style.display = 'block';
        }

        function recalcModal() {
            if (!currentRow) return;
            const profit = parseFloat(document.getElementById('m-profit').value) || 0;
            const cost   = currentRow.prices[currentRatio]?.cost || 0;
            const sell   = Math.round(cost * (1 + profit / 100));
            document.getElementById('m-cost').textContent = '¥' + cost.toLocaleString() + ' /kg';
            document.getElementById('m-sell').textContent = '¥' + sell.toLocaleString() + ' /kg';
        }

        function submitSinglePdf() {
            const profit = parseFloat(document.getElementById('m-profit').value) || 0;
            const cost   = currentRow.prices[currentRatio]?.cost || 0;
            const sell   = Math.round(cost * (1 + profit / 100));

            document.getElementById('pdf-rate').value     = globalUsdJpy;
            document.getElementById('pdf-fusd').value     = globalFusd;
            document.getElementById('pdf-ftype').value    = globalType;
            document.getElementById('pdf-origin').value   = currentRow.origin;
            document.getElementById('pdf-ratio').value    = currentRatio;
            document.getElementById('pdf-sell').value     = sell;
            document.getElementById('pdf-customer').value = globalCustomer;

            document.getElementById('pdfForm').submit();
        }
    </script>
</body>
</html>