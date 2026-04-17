<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-laporan')->only(['index', 'show']);
        $this->middleware('permission:create-laporan')->only(['create', 'store']);
        $this->middleware('permission:update-laporan')->only(['edit', 'update']);
        $this->middleware('permission:delete-laporan')->only(['destroy']);
        $this->middleware('permission:analisis-data')->only(['analisis']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('laporan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('laporan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement store logic
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('laporan.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('laporan.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement update logic
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Implement delete logic
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus');
    }

    /**
     * Export laporan
     */
    public function export()
    {
        // TODO: Implement export logic
        return response()->download('laporan.pdf');
    }

    /**
     * Analisis data
     */
    public function analisis()
    {
        return view('laporan.analisis');
    }
}
