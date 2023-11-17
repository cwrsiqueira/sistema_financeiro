<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
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
        $current_balance = Account::sum('opening_balance');

        return view('accounts.list', [
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
        //
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
