<?php
namespace App\Http\Controllers;

use App\Models\Simulation;
use App\Models\PriceRecord;
use App\Models\Contract;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
    {
        // 直近の計算履歴5件
        $recentSimulations = Simulation::orderBy('created_at', 'desc')
            ->take(5)->get();

        // 最新の為替・価格レコード
        $latestPrice = PriceRecord::orderBy('record_date', 'desc')->first();

        // 得意先数
        $customerCount = Customer::count();

        // 進行中の契約（残数量あり）
        $activeContracts = Contract::where('shipped_kg', '<', \Illuminate\Support\Facades\DB::raw('contract_kg'))
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 今月の出荷金額合計
        $monthlyShipped = Contract::whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->get()
            ->sum(fn($c) => $c->shipped_kg * ($c->unit_price_jpy ?? 0));

        return view('dashboard', compact(
            'recentSimulations',
            'latestPrice',
            'customerCount',
            'activeContracts',
            'monthlyShipped'
        ));
    }
}