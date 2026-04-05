<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Contract;

class CustomerController extends Controller
{
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
        ]);
        return redirect('/customers?customer_id=' . $request->input('customer_id'))
            ->with('success', '契約を登録しました');
    }

    public function addShipment(Request $request)
    {
        $contract = Contract::find($request->input('contract_id'));
        if ($contract) {
            $contract->shipped_kg += $request->input('add_kg');
            $contract->save();
        }

        return redirect('/customers?customer_id=' . $request->input('customer_id'))
            ->with('success', '出荷数量を更新しました');
    }
}
