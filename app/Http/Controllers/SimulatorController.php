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
        // 初期設定
        $usdJpy      = $request->input('usd_jpy', 150);
        $featherUsd  = $request->input('feather_usd', 5);
        $featherType = $request->input('feather_type', 'ホワイトダック');
        $origin      = $request->input('origin', '中国');
        $downRatio   = $request->input('down_ratio', 85);
        $profitRate  = $request->input('profit_rate', 10);
        $customerName = $request->input('customer_name', '');

        // 産地×種類による価格係数
        $coefficients = [
            '中国'     => ['ホワイトダック' => 1.00, 'グレーダック' => 0.85],
            'フランス' => ['ホワイトダック' => 1.35, 'グレーダック' => 1.20],
            'ロシア'   => ['ホワイトダック' => 1.10, 'グレーダック' => 0.95],
            'イタリア' => ['ホワイトダック' => 1.30, 'グレーダック' => 1.15],
            'ウクライナ' => ['ホワイトダック' => 1.05, 'グレーダック' => 0.90],
        ];

        // ダウン比率による補正（85%基準）
        $downCoeff = $downRatio / 85;
        $originCoeff = $coefficients[$origin][$featherType] ?? 1.00;

        // 仕入れ円換算
        $featherJpy = round($featherUsd * $usdJpy * $originCoeff * $downCoeff, 2);

        // 販売単価（利益率込み）
        $salePriceJpy = round($featherJpy * (1 + $profitRate / 100));


        $comment = "・円安（{$usdJpy}円/ドル）により{$origin}産羽毛の輸入コストが上昇しています。\n"
            . "・{$featherType}は{$origin}産で品質評価が高く、需要が継続しています。\n"
            . "・物流費の上昇が最終価格に転嫁されるリスクがあります。";

        // DB保存
        \App\Models\Simulation::create([
            'feather_type' => $featherType,
            'origin'       => $origin,
            'down_ratio'   => $downRatio,
            'feather_usd'  => $featherUsd,
            'usd_jpy'      => $usdJpy,
            'feather_jpy'  => $featherJpy,
            'profit_rate'  => $profitRate,
            'sale_price_jpy' => $salePriceJpy,
            'customer_name' => $customerName,
            'comment'      => $comment,
        ]);

        return view('simulator.result', compact(
            'comment',
            'usdJpy',
            'featherUsd',
            'featherType',
            'origin',
            'featherJpy',
            'downRatio',
            'profitRate',
            'salePriceJpy',
            'customerName'
        ));
    }

    public function pdf(Request $request)
    {
        $usdJpy     = $request->input('usd_jpy', 150);
        $featherUsd = $request->input('feather_usd', 5);
        $comment    = $request->input('comment');

        // PDF生成ロジック
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('simulator.pdf', compact('usdJpy', 'featherUsd', 'comment'));

        return $pdf->download('羽毛価格分析レポート.pdf');
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
