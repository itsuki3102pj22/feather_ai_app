<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Simulation;

class SimulatorController extends Controller
{
    public function form()
    {
        return view('simulator.form');
    }

    public function analyze(Request $request)
    {
        // デフォルト設定
        $usdJpy      = $request->input('usd_jpy', 150);
        $featherUsd  = $request->input('feather_usd', 95);
        $featherType = $request->input('feather_type', 'ホワイトダック');
        $profitRate  = $request->input('profit_rate', 10);
        $customerName = $request->input('customer_name', '');

        // 産地×種類の価格係数
        $coefficients = [
            '中国'       => ['ホワイトダック' => 1.00, 'グレーダック' => 0.85, 'ホワイトグース' => 1.40, 'グレーグース' => 1.20],
            'フランス'   => ['ホワイトダック' => 1.35, 'グレーダック' => 1.20, 'ホワイトグース' => 1.80, 'グレーグース' => 1.60],
            'ロシア'     => ['ホワイトダック' => 1.10, 'グレーダック' => 0.95, 'ホワイトグース' => 1.50, 'グレーグース' => 1.30],
            'イタリア'   => ['ホワイトダック' => 1.30, 'グレーダック' => 1.15, 'ホワイトグース' => 1.70, 'グレーグース' => 1.50],
            'ウクライナ' => ['ホワイトダック' => 1.05, 'グレーダック' => 0.90, 'ホワイトグース' => 1.45, 'グレーグース' => 1.25],
        ];

        // ダウン比率リスト
        $downRatios = [50, 70, 80, 85, 90, 93, 95];

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
        $usdJpy      = $request->input('usd_jpy', 150);
        $featherUsd  = $request->input('feather_usd', 95);
        $featherType = $request->input('feather_type', 'ホワイトダック');
        $origin      = $request->input('origin', '中国');
        $downRatio   = $request->input('down_ratio', 85);
        $sellPrice   = $request->input('sell_price_jpy', 0);
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
        $items = json_decode($request->input('items'), true);
        $customerName = $request->input('customer_name', '');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('simulator.pdf-multiple', compact('items', 'customerName'));

        return $pdf->download('見積書_' . ($customerName ?: '無題') . '.pdf');
    }

    public function history()
    {
        $monthly = \App\Models\Simulation::selectRaw(
            "DATE_FORMAT(created_at, '%Y-%m') as month,
             AVG(feather_jpy) as avg_price,
             AVG(usd_jpy) as avg_rate"
        )
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderByRaw("MIN(created_at) ASC")
            ->get();

        $histories = \App\Models\Simulation::orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('simulator.history', compact('monthly', 'histories'));
    }
}
