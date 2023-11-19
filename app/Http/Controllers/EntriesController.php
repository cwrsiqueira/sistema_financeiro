<?php

namespace App\Http\Controllers;

use App\Models\Entries;
use App\Http\Controllers\Controller;
use Dotenv\Parser\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EntriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $data = $request->except('_token');
        Validator::make($data, [
            "transaction_description" => "required|max:255",
            "transaction_date" => "required",
            "transaction_value" => "required",
            "transaction_type" => "required",
        ])->validate();

        $data['account_id'] = intval($data['account_id']);
        $data['transaction_value'] = str_replace(',', '.', str_replace('.', '', $data['transaction_value']));
        $data['transaction_date'] = $data['transaction_date'] . " " . date('H:i:s');
        $data['user_id'] = Auth::user()->id;

        // dd($data);
        $newEntry = Entries::create($data);

        return redirect()->route('entries.create', ['account' => $data['account_id']])->with('success', 'Lançamento adicionado com sucesso!');
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
    public function edit(Request $request, $id)
    {
        $entry = Entries::find($id);
        $account_id = $request->account_id;
        return view('entries.edit', [
            'entry' => $entry,
            'account_id' => $account_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('_token');
        Validator::make($data, [
            "transaction_description" => "required|max:255",
            "transaction_date" => "required",
            "transaction_value" => "required",
            "transaction_type" => "required",
        ])->validate();

        $data['account_id'] = intval($data['account_id']);
        $data['transaction_value'] = str_replace(',', '.', str_replace('.', '', $data['transaction_value']));
        $data['transaction_date'] = $data['transaction_date'];

        // dd($data);
        $newEntry = Entries::find($id)->update($data);

        return redirect()->route('accounts.show', $data['account_id'])->with('success', 'Lançamento editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        Entries::find($id)->delete();
        return redirect()->route('accounts.show', $request->account_id)->with('success', 'Lançamento excluído com sucesso!');
    }
}
