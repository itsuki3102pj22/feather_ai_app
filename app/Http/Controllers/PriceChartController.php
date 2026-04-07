<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PriceRecord;

class PriceChartController extends Controller
{
    public function index()
    {
        $monthly = PriceRecord::where('period_type', 'monthly')
            ->orderBy('record_date')
            ->get();

        $weekly = PriceRecord::where('period_type', 'weekly')
            ->orderBy('record_date')
            ->latest('record_date')
            ->take(12)
            ->get()
            ->reverse()
            ->values();

        $latestComment = PriceRecord::whereNotNull('manual_comment')
            ->orWhereNotNull('ai_comment')
            ->orderByDesc('record_date')
            ->first();

        return view('price_chart.index', compact('monthly', 'weekly', 'latestComment'));
    }

    public function store(Request $request)
    {
        // 入力バリデーション
        $request->validate([
            'record_date'    => 'required|date',
            'period_type'    => 'required|in:monthly,weekly',
            'white_duck_usd' => 'required|numeric|min:0.01|max:9999',
            'usd_jpy'        => 'required|numeric|min:1|max:1000',
        ], [
            'record_date.required'    => '記録日を選択してください',
            'record_date.date'        => '正しい日付を入力してください',
            'white_duck_usd.required' => 'ドル単価を入力してください',
            'white_duck_usd.min'      => 'ドル単価は0より大きい値を入力してください',
            'usd_jpy.required'        => 'ドル円レートを取得できませんでした。再読み込みしてください',
        ]);

        $usdJpy = $request->input('usd_jpy');
        $whiteDuckUsd = $request->input('white_duck_usd');
        $whiteDuckJpy = $whiteDuckUsd * $usdJpy;
        $greyDuckJpy = $whiteDuckJpy * 0.70;

        PriceRecord::create([
            'record_date' => $request->input('record_date'),
            'period_type' => $request->input('period_type', 'monthly'),
            'usd_jpy' => $usdJpy,
            'white_duck_usd' => $whiteDuckUsd,
            'white_duck_jpy' => $whiteDuckJpy,
            'grey_duck_jpy' => $greyDuckJpy,
        ]);

        return redirect('/price-chart')->with('success', 'データを登録しました。');
    }

    public function updateComment(Request $request)
    {
        $record = PriceRecord::orderByDesc('record_date')->first();
        if ($record) {
            $record->update([
                'manual_comment' => $request->input('manual_comment'),
                'ai_comment' => $request->input('ai_comment'),
            ]);
        }
        return redirect('/price-chart')->with('success', 'コメントを登録しました。');
    }
}
