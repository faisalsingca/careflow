<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transactions.index');
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('transactions.index')->with('success', 'Transaction created.');
    }

    public function show($id)
    {
        return redirect()->route('transactions.index');
    }

    public function edit($id)
    {
        return view('transactions.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('transactions.index')->with('success', 'Transaction updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted.');
    }
}