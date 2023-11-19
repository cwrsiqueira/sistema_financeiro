<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Entries;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');

        $calc_ini_date = Carbon::create($year, $month, 1); // Primeiro dia do mês
        $calc_fin_date = Carbon::create($year, $month, 1)->endOfMonth(); // Último dia do mês

        $selected_days = [];
        $selected_dates = [];

        for ($date = $calc_ini_date; $date->lte($calc_fin_date); $date->addDay()) {
            $selected_days[] = $date->format('d');
            $selected_dates[] = $date->format('Y-m-d 23:59:59');
        }
        $selected_days = implode(',', $selected_days);

        $accounts = Account::all();
        $opening_balance = Account::sum('opening_balance');

        $current_day_inflows = [];
        $current_day_outflows = [];
        foreach ($selected_dates as $date) {
            $current_inflows = Entries::where('transaction_type', 'inflow')
                ->where('transaction_date', '<=', $date)
                ->sum('transaction_value');

            $current_outflows = Entries::where('transaction_type', 'outflow')
                ->where('transaction_date', '<=', $date)
                ->sum('transaction_value');

            $current_day_inflows[] = $current_inflows;
            $current_day_outflows[] = $current_outflows;
            $current_day_balance[] = $opening_balance + $current_inflows - $current_outflows;
        }
        $current_day_inflows = implode(',', $current_day_inflows);
        $current_day_outflows = implode(',', $current_day_outflows);
        $current_day_balance = implode(',', $current_day_balance);

        $current_account_balance = [];
        foreach ($accounts as $key => $account) {
            foreach ($selected_dates as $date) {
                $opening_balance = Account::where('id', $account->id)->sum('opening_balance');

                $current_inflows = Entries::where('account_id', $account->id)
                    ->where('transaction_type', 'inflow')
                    ->where('transaction_date', '<=', $date)
                    ->sum('transaction_value');

                $current_outflows = Entries::where('account_id', $account->id)
                    ->where('transaction_type', 'outflow')
                    ->where('transaction_date', '<=', $date)
                    ->sum('transaction_value');

                $current_account_balance[$account->name][] = $opening_balance + $current_inflows - $current_outflows;
            }
        }

        $balance_accounts = [];
        foreach ($current_account_balance as $key => $item) {
            $balance_account = [
                'label' => $key,
                'data' => $item,
                'borderWidth' => 1
            ];
            $balance_accounts[] = $balance_account;
        }

        return view('dashboard', [
            'accounts' => $accounts,
            'opening_balance' => $opening_balance,
            'selected_days' => $selected_days,
            'current_day_balance' => $current_day_balance,
            'current_day_inflows' => $current_day_inflows,
            'current_day_outflows' => $current_day_outflows,
            'balance_accounts' => $balance_accounts,
            'month' => $month,
            'year' => $year,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
