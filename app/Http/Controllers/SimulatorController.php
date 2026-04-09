<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Simulation;
use App\Constants\FeatherConstants;

class SimulatorController extends Controller
{
    public function form()
    {
        return view('simulator.form', [
            'featherTypes' => FeatherConstants::FEATHER_TYPES,
            'origins' => FeatherConstants::ORIGINS,
        ]);
    }

    public function analyze(Request $request)
    {
        //　入力バリデーション
        $request->validate([
            'usd_jpy'       => 'required|numeric|min:1|max:1000',
            'feather_usd'   => 'required|numeric|min:0.01|max:9999',
            'feather_type'  => FeatherConstants::featherTypeRule(),
            'origin'        => FeatherConstants::originRule(),
            'profit_rate'   => 'required|numeric|min:0|max:100',
            'customer_name' => 'nullable|string|max:100',
        ], [
            'usd_jpy.required'      => 'ドル円レートを入力してください',
            'usd_jpy.numeric'       => 'ドル円レートは数値で入力してください',
            'usd_jpy.min'           => 'ドル円レートが低すぎます',
            'usd_jpy.max'           => 'ドル円レートが高すぎます',
            'feather_usd.required'  => 'ドル単価を入力してください',
            'feather_usd.numeric'   => 'ドル単価は数値で入力してください',
            'feather_usd.min'       => 'ドル単価は0より大きい値を入力してください',
            'profit_rate.required'  => '利益率を入力してください',
            'profit_rate.min'       => '利益率は0%以上で入力してください',
            'profit_rate.max'       => '利益率は100%以下で入力してください',
            'feather_type.in'       => '羽毛種の選択が正しくありません',
            'origin.in'             => '産地の選択が正しくありません',
        ]);

        // デフォルト設定
        $usdJpy      = $request->input('usd_jpy', 150);
        $featherUsd  = $request->input('feather_usd', 95);
        $featherType = $request->input('feather_type', FeatherConstants::FEATHER_TYPES[0]);
        $profitRate  = $request->input('profit_rate', 10);
        $customerName = $request->input('customer_name', '');

        // 産地×種類の価格係数
        $coefficients = [
            '中国'       => ['ホワイトダック' => 1.00, 'グレーダック' => 0.85, 'ホワイトグース' => 1.40, 'グレーグース' => 1.20],
            'フランス'   => ['ホワイトダック' => 1.35, 'グレーダック' => 1.20, 'ホワイトグース' => 1.80, 'グレーグース' => 1.60],
            'ロシア'     => ['ホワイトダック' => 1.10, 'グレーダック' => 0.95, 'ホワイトグース' => 1.50, 'グレーグース' => 1.30],
            'イタリア'   => ['ホワイトダック' => 1.30, 'グレーダック' => 1.15, 'ホワイトグース' => 1.70, 'グレーグース' => 1.50],
            'ウクライナ' => ['ホワイトダック' => 1.05, 'グレーダック' => 0.90, 'ホワイトグース' => 1.45, 'グレーグース' => 1.25],
            'ポーランド' => ['ホワイトダック' => 1.10, 'グレーダック' => 0.95, 'ホワイトグース' => 1.50, 'グレーグース' => 1.30],
        ];

        // ダウン比率リスト
        $downRatios = FeatherConstants::DOWN_RATIOS;

        // 産地×ダウン比率の全組み合わせで計算
        $priceTable = [];
        foreach ($coefficients as $origin => $types) {
            $originCoeff = $types[$featherType] ?? 1.00;
            $row = ['origin' => $origin, 'prices' => []];
            foreach ($downRatios as $ratio) {
                $downCoeff    = $ratio / 85;
                $costJpy      = $featherUsd * $usdJpy * $originCoeff * $downCoeff;
                $sellJpy      = $costJpy * (1 + $profitRate / 100);
                $row['prices'][$ratio] = [
                    'cost' => round($costJpy, 0),
                    'sell' => round($sellJpy, 0),
                ];
            }
            $priceTable[] = $row;
        }

        // DB保存（85%・中国を代表値として保存）
        $baseCoeff   = $coefficients['中国'][$featherType] ?? 1.00;
        $priceJpy    = $featherUsd * $usdJpy * $baseCoeff;
        $salePriceJpy = $priceJpy * (1 + $profitRate / 100);

        \App\Models\Simulation::create([
            'feather_type'    => $featherType,
            'origin'          => '中国',
            'down_ratio'      => 85,
            'feather_usd'     => $featherUsd,
            'usd_jpy'         => $usdJpy,
            'feather_jpy'     => $priceJpy,
            'profit_rate'     => $profitRate,
            'sale_price_jpy'  => $salePriceJpy,
            'customer_name'   => $customerName,
            'comment'         => '',
        ]);

        return view('simulator.result', compact(
            'usdJpy',
            'featherUsd',
            'featherType',
            'profitRate',
            'customerName',
            'priceTable',
            'downRatios'
        ));
    }

    public function pdf(Request $request)
    {
        $request->validate([
            'usd_jpy'      => 'required|numeric|min:1|max:1000',
            'feather_usd'  => 'required|numeric|min:0.01|max:9999',
            'feather_type' => 'required|in:ホワイトダック,グレーダック,ホワイトグース,グレーグース',
            'origin'       => 'required|in:中国,フランス,ロシア,イタリア,ウクライナ,ポーランド',
            'down_ratio'   => 'required|numeric|in:50,70,75,80,85,90,93,95',
            'sell_price_jpy' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:100',
        ]);

        $usdJpy      = $request->input('usd_jpy');
        $featherUsd  = $request->input('feather_usd');
        $featherType = $request->input('feather_type');
        $origin      = $request->input('origin');
        $downRatio   = $request->input('down_ratio');
        $sellPrice   = $request->input('sell_price_jpy');
        $customerName = $request->input('customer_name', '');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('simulator.pdf', compact(
            'usdJpy',
            'featherUsd',
            'featherType',
            'origin',
            'downRatio',
            'sellPrice',
            'customerName'
        ));

        return $pdf->download('見積書_' . ($customerName ?: '無題') . '.pdf');
    }

    public function pdfMultiple(Request $request)
    {
        $request->validate([
            'items' => 'required|json',
            'customer_name' => 'nullable|string|max:100',
        ]);

        $items = json_decode($request->input('items'), true);
        if (!is_array($items) || empty($items)) {
            return redirect('/simulator')->withErrors(['items' => '1件以上の品種を選択してください。']);
        }

        $customerName = $request->input('customer_name', '');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('simulator.pdf-multiple', compact('items', 'customerName'));

        return $pdf->download('見積書_' . ($customerName ?: '無題') . '.pdf');
    }
    public function history()
    {
        // 月別集計：(ドル単価 * 為替) を計算してから平均(AVG)を取る
        $monthly = \App\Models\Simulation::selectRaw(
            "DATE_FORMAT(created_at, '%Y-%m') as month,
         AVG(feather_usd * usd_jpy) as avg_price,
         AVG(usd_jpy) as avg_rate"
        )
            ->groupBy("month")
            ->orderBy("month", "ASC")
            ->get();

        // 直近20件の履歴
        $histories = \App\Models\Simulation::orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('simulator.history', compact('monthly', 'histories'));
    }

    public function result(Simulation $simulation)
    {
        // 産地×種類の価格係数
        $coefficients = [
            '中国'       => ['ホワイトダック' => 1.00, 'グレーダック' => 0.85, 'ホワイトグース' => 1.40, 'グレーグース' => 1.20],
            'フランス'   => ['ホワイトダック' => 1.35, 'グレーダック' => 1.20, 'ホワイトグース' => 1.80, 'グレーグース' => 1.60],
            'ロシア'     => ['ホワイトダック' => 1.10, 'グレーダック' => 0.95, 'ホワイトグース' => 1.50, 'グレーグース' => 1.30],
            'イタリア'   => ['ホワイトダック' => 1.30, 'グレーダック' => 1.15, 'ホワイトグース' => 1.70, 'グレーグース' => 1.50],
            'ウクライナ' => ['ホワイトダック' => 1.05, 'グレーダック' => 0.90, 'ホワイトグース' => 1.45, 'グレーグース' => 1.25],
            'ポーランド' => ['ホワイトダック' => 1.10, 'グレーダック' => 0.95, 'ホワイトグース' => 1.50, 'グレーグース' => 1.30],
        ];

        // ダウン比率リスト
        $downRatios = FeatherConstants::DOWN_RATIOS;

        // 産地×ダウン比率の全組み合わせで計算
        $priceTable = [];
        foreach ($coefficients as $origin => $types) {
            $originCoeff = $types[$simulation->feather_type] ?? 1.00;
            $row = ['origin' => $origin, 'prices' => []];
            foreach ($downRatios as $ratio) {
                $downCoeff    = $ratio / 85;
                $costJpy      = $simulation->feather_usd * $simulation->usd_jpy * $originCoeff * $downCoeff;
                $sellJpy      = $costJpy * (1 + $simulation->profit_rate / 100);
                $row['prices'][$ratio] = [
                    'cost' => round($costJpy, 0),
                    'sell' => round($sellJpy, 0),
                ];
            }
            $priceTable[] = $row;
        }

        return view('simulator.result', compact(
            'simulation',
            'priceTable',
            'downRatios'
        ));
    }
}
