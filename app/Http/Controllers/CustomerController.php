<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Contract;
use App\Constants\FeatherConstants;

class CustomerController extends Controller
{
    // 得意先一覧と契約情報表示
    public function index(Request $request)
    {
        $customers = Customer::orderBy('name')->get();
        $selectedCustomer = null;
        $contracts = collect();

        if ($request->has('customer_id') && $request->customer_id) {
            $selectedCustomer = Customer::find($request->customer_id);
            if (!$selectedCustomer) {
                return redirect('/customers')->withErrors(['customer_id' => '得意先が見つかりません。']);
            }
            $contracts = Contract::where('customer_id', $request->customer_id)
                ->orderBy('season', 'desc')
                ->orderBy('feather_type')
                ->get();
        }
        return view('customers.index', [
            'customers' => $customers,
            'selectedCustomer' => $selectedCustomer,
            'contracts' => $contracts,
            'featherTypes' => FeatherConstants::FEATHER_TYPES,
            'origins' => FeatherConstants::ORIGINS,
            'downRatios' => FeatherConstants::DOWN_RATIOS,
        ]);
    }

    // 得意先登録
    public function store(Request $request)
    {
        // 入力バリデーション
        $request->validate([
            'name'    => 'required|string|max:100',
            'contact' => 'nullable|string|max:50',
            'phone'   => 'nullable|string|max:20',
            'note'    => 'nullable|string|max:500',
        ], [
            'name.required' => '会社名を入力してください',
            'name.max'      => '会社名は100文字以内で入力してください',
        ]);

        Customer::create([
            'name' => $request->input('name'),
            'contact' => $request->input('contact'),
            'phone' => $request->input('phone'),
            'note' => $request->input('note'),
        ]);
        return redirect('/customers')->with('success', '得意先を登録しました。');
    }

    // 契約登録
    public function storeContract(Request $request)
    {
        // 入力バリデーション
        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'season'         => 'required|string|max:20',
            'feather_type'   => FeatherConstants::featherTypeRule(),
            'origin'         => FeatherConstants::originRule(),
            'down_ratio'     => FeatherConstants::downRatioRule(),
            'contract_kg'    => 'required|numeric|min:1.0|max:999999',
            'unit_price_jpy' => 'nullable|numeric|min:1.0|max:999999',
            'comment'        => 'nullable|string|max:500',
        ], [
            'customer_id.required'  => '得意先を選択してください',
            'customer_id.exists'    => '得意先が見つかりません',
            'season.required'       => 'シーズンを入力してください',
            'feather_type.required' => '羽毛種を選択してください',
            'feather_type.in'       => '羽毛種の選択が正しくありません',
            'origin.required'       => '産地を選択してください',
            'origin.in'             => '産地の選択が正しくありません',
            'down_ratio.required'   => 'ダウン比率を選択してください',
            'contract_kg.required'  => '契約数量を入力してください',
            'contract_kg.min'       => '契約数量は1.0kg以上で入力してください',
        ]);

        Contract::create([
            'customer_id'    => $request->input('customer_id'),
            'season'         => $request->input('season'),
            'feather_type'   => $request->input('feather_type'),
            'origin'         => $request->input('origin'),
            'down_ratio'     => $request->input('down_ratio'),
            'contract_kg'    => $request->input('contract_kg'),
            'shipped_kg'     => 0,
            'unit_price_jpy' => $request->input('unit_price_jpy'),
            'comment'        => $request->input('comment'),
        ]);
        return redirect('/customers?customer_id=' . $request->input('customer_id'))
            ->with('success', '契約を登録しました');
    }

    // 出荷数量
    public function addShipment(Request $request)
    {
        // 入力バリデーション
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'contract_id' => 'required|exists:contracts,id',
            'add_kg'      => 'required|numeric|min:1.0',
        ], [
            'customer_id.required' => '得意先が必要です',
            'customer_id.exists'   => '選択された得意先が存在しません',
            'add_kg.required'      => '出荷数量を入力してください',
            'add_kg.min'           => '出荷数量は1.0kg以上で入力してください',
        ]);

        $contract = Contract::find($request->input('contract_id'));
        if (!$contract || $contract->customer_id != $request->input('customer_id')) {
            return redirect('/customers?customer_id=' . $request->input('customer_id'))
                ->withErrors(['contract_id' => '指定された契約が選択した得意先に存在しません。']);
        }

        $remaining = $contract->contract_kg - $contract->shipped_kg;
        $addKg = min($request->input('add_kg'), $remaining); // 追加数量は残数量までに制限

        if ($addKg > 0) {
            $contract->shipped_kg += $addKg;
            $contract->save();
            $message = $request->input('add_kg') > $remaining
                ? $remaining . 'kgを出荷しました（残数量まで調整）。'
                : $addKg . 'kgを出荷いたしました。';
        } else {
            $message = '契約数量に達しているため出荷できません。';
        }

        return redirect('/customers?customer_id=' . $request->input('customer_id'))
            ->with('success', $message);
    }

    // 削除
    public function destroyContract(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'contract_id' => 'required|exists:contracts,id',
        ]);

        $contract = Contract::find($request->input('contract_id'));
        if (!$contract || $contract->customer_id != $request->input('customer_id')) {
            return redirect('/customers?customer_id=' . $request->input('customer_id'))
                ->withErrors(['contract_id' => '指定された契約が選択した得意先に存在しません。']);
        }

        $contract->delete();

        return redirect('/customers?customer_id=' . $request->input('customer_id'))
            ->with('success', '契約を削除しました');
    }
}
