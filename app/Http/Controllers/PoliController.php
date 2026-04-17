<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Poli;

class PoliController extends Controller
{
    /**
     * Display a listing of the poli.
     */
    public function index(): View
    {
        $polis = Poli::orderBy('kode')->paginate(10);
        
        return view('poli.index', compact('polis'));
    }

    /**
     * Show the form for creating a new poli.
     */
    public function create(): View
    {
        return view('poli.create');
    }

    /**
     * Store a newly created poli in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:polis,nama',
            'kode' => 'required|string|max:50|unique:polis,kode',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Poli::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('poli.index')
            ->with('success', 'Poli berhasil ditambahkan.');
    }

    /**
     * Display the specified poli.
     */
    public function show($id): View
    {
        $poli = Poli::findOrFail($id);
        return view('poli.show', compact('poli'));
    }

    /**
     * Show the form for editing the specified poli.
     */
    public function edit($id): View
    {
        $poli = Poli::findOrFail($id);
        return view('poli.edit', compact('poli'));
    }

    /**
     * Update the specified poli in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $poli = Poli::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255|unique:polis,nama,' . $poli->id,
            'kode' => 'required|string|max:50|unique:polis,kode,' . $poli->id,
            'deskripsi' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
        ]);

        $poli->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $poli->is_active
        ]);

        return redirect()->route('poli.index')
            ->with('success', 'Poli berhasil diperbarui.');
    }

    /**
     * Remove the specified poli from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $poli = Poli::findOrFail($id);
        
        // Check if poli has related patients
        if ($poli->pasien()->count() > 0) {
            return redirect()->route('poli.index')
                ->with('error', 'Poli tidak dapat dihapus karena masih memiliki pasien terkait.');
        }

        $poli->delete();

        return redirect()->route('poli.index')
            ->with('success', 'Poli berhasil dihapus.');
    }

    /**
     * Toggle poli status (active/inactive)
     */
    public function toggleStatus($id): RedirectResponse
    {
        $poli = Poli::findOrFail($id);
        
        $poli->update([
            'is_active' => !$poli->is_active
        ]);

        $status = $poli->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('poli.index')
            ->with('success', "Poli berhasil {$status}.");
    }
}
