<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return view('inventory.index');
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('inventory.index')->with('success', 'Item created.');
    }

    public function show($id)
    {
        return redirect()->route('inventory.index');
    }

    public function edit($id)
    {
        return view('inventory.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('inventory.index')->with('success', 'Item updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('inventory.index')->with('success', 'Item deleted.');
    }
}