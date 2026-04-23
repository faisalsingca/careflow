<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return view('rooms.index');
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('rooms.index')->with('success', 'Room created.');
    }

    public function show($id)
    {
        return redirect()->route('rooms.index');
    }

    public function edit($id)
    {
        return view('rooms.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('rooms.index')->with('success', 'Room updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('rooms.index')->with('success', 'Room deleted.');
    }
}