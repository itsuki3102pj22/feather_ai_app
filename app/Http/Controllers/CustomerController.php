<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Contract;

class CustomerController extends Controller
{
    // 得意先一覧と契約情報表示
    public function index(Request $request)
    {
        $customers = Customer::all();
        $selectedCustomer = null;
        $contracts = collect();

        if ($request->has('customer_id') && $request->customer_id) {
            $selectedCustomer = Customer::find($request->customer_id);
            $contracts = Contract::where('customer_id', $request->customer_id)
                ->orderBy('season', 'desc')
                ->orderBy('feather_type')
                ->get();
        }
        return view('customers.index', compact('customers', 'selectedCustomer', 'contracts'));
    }

    // 得意先登録
    public function store(Request $request)
    {
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
        $contract = Contract::find($request->input('contract_id'));

        if ($contract) {
            $remaining = $contract->contract_kg - $contract->shipped_kg;
            $addKg = min($request->input('add_kg'), $remaining); // 追加数量は残数量までに制限

            if ($addKg > 0) {
                $contract->shipped_kg += $addKg;
                $contract->save();
                $message = $addKg . 'kgを出荷いたしました。';
            } else {
                $message = '契約数量に達しているため出荷できません。';
            }
        }

        return redirect('/customers?customer_id=' . $request->input('customer_id'))
            ->with('success', $message ?? '出荷情報を更新しました');
    }

    // 削除
    public function destroyContract(Request $request)
    {
        $contractId = $request->input('contract_id');
        $customerId = $request->input('customer_id');

        $contract = Contract::find($contractId);

        if ($contract) {
            $contract->delete();
            $message = '契約を削除しました';
        } else {
            $message = '契約が見つかりませんでした';
        }

        return redirect('/customers?customer_id=' . $customerId)
            ->with('success', $message);
    }
}
