<?php

namespace App\Http\Controllers;

use App\Models\Entries;
use App\Http\Controllers\Controller;
use Dotenv\Parser\Entry;
use Illuminate\Http\Request;

class EntriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entries = Entries::all();
        $current_balance = Entries::sum('transaction_value');
        return view('entries.list', [
            'entries' => $entries,
            'current_balance' => $current_balance,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('entries.create');
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
    public function show(Entries $entries)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entries $entries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entries $entries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entries $entries)
    {
        //
    }
}
