<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Name;

class NameController extends Controller
{

    public function create()
    {
        return view('login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Name::create([
            'name' => $request->input('name'),
        ]);
        return redirect()->back()->with('success', 'Name added successfully.');
    }
}
