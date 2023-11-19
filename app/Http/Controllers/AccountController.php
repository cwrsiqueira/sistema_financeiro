<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Entries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $accounts = Account::all();
        $current_balance = 0;
        foreach ($accounts as $key => $item) {
            $inflows = Entries::where('account_id', $item->id)
                ->where('transaction_type', 'inflow')
                ->sum('transaction_value');

            $outflows = Entries::where('account_id', $item->id)
                ->where('transaction_type', 'outflow')
                ->sum('transaction_value');

            $accounts[$key]['inflows'] = $inflows;
            $accounts[$key]['outflows'] = $outflows;
            $accounts[$key]['balance'] = $item->opening_balance + $inflows - $outflows;
            $current_balance += $accounts[$key]['balance'];
        }

        return view('accounts.index', [
            'user' => $request->user(),
            'accounts' => $accounts,
            'current_balance' => $current_balance,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $opening_balance = $request->opening_balance;

        Validator::make($request->except('_token'), [
            'name' => 'required'
        ], [
            'required' => __('The Account Name is required.')
        ])->validate();

        $opening_balance = str_replace(',', '.', str_replace('.', '', $opening_balance));

        $newAccount = new Account();
        $newAccount->name = $name;
        $newAccount->opening_balance = $opening_balance;
        if (!$newAccount->save()) {
            return redirect()->route('accounts.index')->with('error', __('An error has occurred. Account not created!'));
            exit;
        }

        return redirect()->route('accounts.index')->with('success', __('Account created successfully!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $data = $request->all();
        $ini_date = $data['ini_date'] ?? date('Y-m-01 00:00:00');
        $fin_date = $data['fin_date'] ?? date('Y-m-t 23:59:59');

        $account = Account::find($id);

        $entries = Entries::where('account_id', $id)
            ->whereBetween('transaction_date', [$ini_date, $fin_date])
            ->orderBy('transaction_date')
            ->get();

        $ob_inflows = Entries::where('account_id', $id)
            ->where('transaction_type', 'inflow')
            ->where('transaction_date', '<', $ini_date)
            ->sum('transaction_value');

        $ob_outflows = Entries::where('account_id', $id)
            ->where('transaction_type', 'outflow')
            ->where('transaction_date', '<', $ini_date)
            ->sum('transaction_value');

        $opening_balance = $account->opening_balance + $ob_inflows - $ob_outflows;

        $inflows = Entries::where('account_id', $id)
            ->where('transaction_type', 'inflow')
            ->sum('transaction_value');

        $outflows = Entries::where('account_id', $id)
            ->where('transaction_type', 'outflow')
            ->sum('transaction_value');

        $balance = $account->opening_balance + $inflows - $outflows;

        foreach ($entries as $key => $item) {
            $current_inflow = Entries::where('account_id', $id)
                ->where('transaction_type', 'inflow')
                ->where('transaction_date', '<=', $item->transaction_date)
                ->sum('transaction_value');
            $current_outflow = Entries::where('account_id', $id)
                ->where('transaction_type', 'outflow')
                ->where('transaction_date', '<=', $item->transaction_date)
                ->sum('transaction_value');
            $current_balance = $account->opening_balance + $current_inflow - $current_outflow;
            $entries[$key]['current_balance'] = $current_balance;
        }

        // dd($entries);

        return view('accounts.show', [
            'account' => $account,
            'entries' => $entries,
            'opening_balance' => $opening_balance,
            'inflows' => $inflows,
            'outflows' => $outflows,
            'balance' => $balance,
            'ini_date' => $ini_date,
            'fin_date' => $fin_date,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $account = Account::find($id);
        return view('accounts.edit', [
            'account' => $account,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $name = $request->name;
        $opening_balance = $request->opening_balance;

        Validator::make($request->except('_token'), [
            'name' => 'required'
        ], [
            'required' => 'The Account Name is required.'
        ])->validate();

        $opening_balance = str_replace(',', '.', str_replace('.', '', $opening_balance));

        $editAccount = Account::find($id);
        $editAccount->name = $name;
        $editAccount->opening_balance = $opening_balance;
        if (!$editAccount->save()) {
            return redirect()->route('accounts.index')->with('error', __('An error has occurred. Account not edited!'));
            exit;
        }

        return redirect()->route('accounts.index')->with('success', __('Account edited successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Account::destroy($id)) {
            return redirect()->route('accounts.index')->with('error', __('An error has occurred. Account not deleted!'));
            exit;
        }

        return redirect()->route('accounts.index')->with('success', __('Account deleted successfully!'));
    }
}
