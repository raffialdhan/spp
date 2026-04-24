<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        $fees = Fee::where('is_active', true)->orderBy('name')->get();
        $archived = Fee::where('is_active', false)->orderBy('name')->get();
        
        return view('admin.fees', compact('fees', 'archived'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Bulanan,Sekali Bayar,Tahunan',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Fee::create($validated);

        return redirect()->route('admin.fees')->with('success', 'Kategori tagihan berhasil ditambahkan!');
    }

    public function update(Request $request, Fee $fee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Bulanan,Sekali Bayar,Tahunan',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $fee->update($validated);

        return redirect()->route('admin.fees')->with('success', 'Kategori tagihan berhasil diperbarui!');
    }

    public function toggleStatus(Fee $fee)
    {
        $fee->update(['is_active' => !$fee->is_active]);
        return redirect()->route('admin.fees')->with('success', 'Status tagihan berhasil diubah!');
    }
}
