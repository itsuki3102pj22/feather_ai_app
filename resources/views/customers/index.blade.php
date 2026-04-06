<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>得意先管理</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 960px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #8e44ad;
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

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        select,
        input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
        }

        .btn {
            padding: 9px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }

        .btn-purple {
            background: #8e44ad;
            color: white;
        }

        .btn-blue {
            background: #2980b9;
            color: white;
        }

        .btn-green {
            background: #27ae60;
            color: white;
        }

        .btn-sm {
            padding: 4px 12px;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #8e44ad;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 13px;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }

        tr:hover td {
            background: #f0f0f0;
        }

        .progress-bar {
            background: #ddd;
            border-radius: 4px;
            height: 10px;
            width: 100%;
        }

        .progress-fill {
            background: #27ae60;
            border-radius: 4px;
            height: 10px;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
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

        .alert {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            padding: 10px 16px;
            margin-bottom: 16px;
            color: #155724;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 100;
        }

        .modal {
            background: white;
            border-radius: 8px;
            padding: 28px;
            width: 480px;
            margin: 80px auto;
        }

        .modal h2 {
            margin-top: 0;
        }

        .form-group {
            margin: 12px 0;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 4px;
            color: #555;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            box-sizing: border-box;
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

    <h1>得意先管理</h1>

    @if(session('success'))
    <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="top-bar">
        <div style="display:flex;align-items:center;gap:12px;">
            <label style="font-weight:bold;">得意先を選択：</label>
            <form method="GET" action="/customers" id="selectForm">
                <select name="customer_id" onchange="document.getElementById('selectForm').submit()" style="min-width:200px;">
                    <option value="">― 選択してください ―</option>
                    @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ optional($selectedCustomer)->id == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
        <button class="btn btn-purple" onclick="document.getElementById('newCustomerModal').style.display='block'">
            ＋ 新規取引先追加
        </button>
    </div>

    @if($selectedCustomer)
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h2 style="margin:0;">{{ $selectedCustomer->name }}</h2>
                @if($selectedCustomer->contact)
                <p style="margin:4px 0;color:#666;font-size:14px;">担当：{{ $selectedCustomer->contact }}　{{ $selectedCustomer->phone }}</p>
                @endif
            </div>
            <button class="btn btn-blue" onclick="document.getElementById('newContractModal').style.display='block'">
                ＋ 契約を追加
            </button>
        </div>
    </div>

    @if($contracts->count() > 0)
    <div class="card">
        <h3 style="margin-top:0;">契約一覧</h3>
        <table>
            <thead>
                <tr>
                    <th>シーズン</th>
                    <th>種別</th>
                    <th>産地</th>
                    <th>比率</th>
                    <th>契約(kg)</th>
                    <th>出荷(kg)</th>
                    <th>残(kg)</th>
                    <th>進捗</th>
                    <th>契約金額</th>
                    <th>出荷金額</th>
                    <th>コメント</th>
                    <th>出荷追加</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contracts as $c)
                <tr>
                    <td>{{ $c->season }}</td>
                    <td>
                        <span class="badge {{ str_contains($c->feather_type, 'ホワイト') ? 'white' : 'grey' }}">
                            {{ $c->feather_type }}
                        </span>
                    </td>
                    <td>{{ $c->origin }}</td>
                    <td>{{ $c->down_ratio }}%</td>
                    <td>{{ number_format($c->contract_kg, 1) }}</td>
                    <td>{{ number_format($c->shipped_kg, 1) }}</td>
                    <td style="color:{{ $c->remaining_kg <= 0 ? '#27ae60' : '#e74c3c' }};font-weight:bold;">
                        {{ number_format($c->remaining_kg, 1) }}
                    </td>
                    <td style="min-width:80px;">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width:{{ min($c->progress_rate, 100) }}%;"></div>
                        </div>
                        <span style="font-size:12px;color:#666;">{{ $c->progress_rate }}%</span>
                    </td>
                    <td>
                        @if($c->unit_price_jpy)
                        ¥{{ number_format($c->total_amount, 0) }}
                        @else ― @endif
                    </td>
                    <td>
                        @if($c->unit_price_jpy)
                        ¥{{ number_format($c->shipped_amount, 0) }}
                        @else ― @endif
                    </td>
                    <td style="font-size:12px;color:#666;max-width:120px;">
                        {{ $c->comment ?: '―' }}
                    </td>
                    <td>
                        @if($c->remaining_kg > 0)
                        <form method="POST" action="/customers/ship" style="display:flex;gap:4px;">
                            @csrf
                            <input type="hidden" name="contract_id" value="{{ $c->id }}">
                            <input type="hidden" name="customer_id" value="{{ $selectedCustomer->id }}">
                            <input type="number" name="add_kg" step="0.1" min="0.1"
                                max="{{ $c->remaining_kg }}"
                                style="width:70px;padding:4px;" placeholder="kg">
                            <button type="submit" class="btn btn-green btn-sm">出荷</button>
                        </form>
                        @else
                        <span style="color:#27ae60;font-size:12px;font-weight:bold;">完了</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="/customers/contract/destroy"
                            onsubmit="return confirm('この契約を削除しますか？')">
                            @csrf
                            <input type="hidden" name="contract_id" value="{{ $c->id }}">
                            <input type="hidden" name="customer_id" value="{{ $selectedCustomer->id }}">
                            <button type="submit" class="btn btn-sm"
                                style="background:#e74c3c;color:white;">削除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="card" style="text-align:center;color:#999;">
        契約がまだ登録されていません。「契約を追加」から登録してください。
    </div>
    @endif
    @endif

    <!-- 新規取引先モーダル -->
    <div class="modal-overlay" id="newCustomerModal" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal">
            <h2>新規取引先追加</h2>
            <form method="POST" action="/customers">
                @csrf
                <div class="form-group">
                    <label>会社名 *</label>
                    <input type="text" name="name" required placeholder="例：○○寝具株式会社">
                </div>
                <div class="form-group">
                    <label>担当者名</label>
                    <input type="text" name="contact" placeholder="例：山田 太郎">
                </div>
                <div class="form-group">
                    <label>電話番号</label>
                    <input type="text" name="phone" placeholder="例：03-1234-5678">
                </div>
                <div class="form-group">
                    <label>備考</label>
                    <input type="text" name="note" placeholder="メモなど">
                </div>
                <div style="display:flex;gap:8px;margin-top:16px;">
                    <button type="submit" class="btn btn-purple" style="flex:1;">登録する</button>
                    <button type="button" class="btn" style="flex:1;background:#95a5a6;color:white;" onclick="document.getElementById('newCustomerModal').style.display='none'">キャンセル</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 契約追加モーダル -->
    <div class="modal-overlay" id="newContractModal" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal">
            <h2>契約を追加</h2>
            <form method="POST" action="/customers/contract">
                @csrf
                <input type="hidden" name="customer_id" value="{{ optional($selectedCustomer)->id }}">
                <div class="form-group">
                    <label>シーズン</label>
                    <input type="text" name="season" value="{{ date('Y') . '-' . (date('Y')+1) }}" placeholder="例：2025-2026">
                </div>
                <div class="form-group">
                    <label>羽毛種</label>
                    <select name="feather_type">
                        <option value="ホワイトダック">ホワイトダック</option>
                        <option value="ホワイトグース">ホワイトグース</option>
                        <option value="グレーダック">グレーダック</option>
                        <option value="グレーグース">グレーグース</option>
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
                    <label>ダウン比率</label>
                    <select name="down_ratio">
                        <option value="50">50%</option>
                        <option value="70">70%</option>
                        <option value="75">75%</option>
                        <option value="80">80%</option>
                        <option value="85" selected>85%</option>
                        <option value="90">90%</option>
                        <option value="93">93%</option>
                        <option value="95">95%</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>契約数量（kg）</label>
                    <input type="number" name="contract_kg" step="0.1" min="0" required placeholder="例：500">
                </div>
                <div class="form-group">
                    <label>契約単価（円/kg）※任意</label>
                    <input type="number" name="unit_price_jpy" step="0.1" min="0" placeholder="例：3500">
                </div>
                <div class="form-group">
                    <label>コメント（任意）</label>
                    <input type="text" name="comment" placeholder="備考・特記事項など">
                </div>
                <div style="display:flex;gap:8px;margin-top:16px;">
                    <button type="submit" class="btn btn-blue" style="flex:1;">登録する</button>
                    <button type="button" class="btn" style="flex:1;background:#95a5a6;color:white;" onclick="document.getElementById('newContractModal').style.display='none'">キャンセル</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>