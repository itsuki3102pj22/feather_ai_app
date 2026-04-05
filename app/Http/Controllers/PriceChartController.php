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
