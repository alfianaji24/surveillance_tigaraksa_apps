<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ICD10Code;
use App\Services\SatuSehatService;

class ICD10Controller extends Controller
{
    protected SatuSehatService $satuSehatService;

    public function __construct(SatuSehatService $satuSehatService)
    {
        $this->satuSehatService = $satuSehatService;
        $this->middleware('auth');
    }

    /**
     * Display ICD-10 codes list
     */
    public function index()
    {
        $codes = ICD10Code::orderBy('code')
            ->paginate(20);

        return view('icd10.index', compact('codes'));
    }

    /**
     * Show form to create new ICD-10 code
     */
    public function create()
    {
        return view('icd10.create');
    }

    /**
     * Store new ICD-10 code
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:icd10_codes,code',
            'display' => 'required|string|max:255'
        ]);

        // Create dengan simple structure
        ICD10Code::create([
            'code' => $validated['code'],
            'display' => $validated['display']
        ]);

        // Check if request is AJAX (from modal)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kode ICD-10 berhasil ditambahkan.',
                'redirect' => route('icd10.index')
            ]);
        }

        return redirect()->route('icd10.index')
            ->with('success', 'Kode ICD-10 berhasil ditambahkan.');
    }

    /**
     * Show ICD-10 code details
     */
    public function show($id)
    {
        $code = ICD10Code::findOrFail($id);
        return view('icd10.show', compact('code'));
    }

    /**
     * Show form to edit ICD-10 code
     */
    public function edit($id)
    {
        $code = ICD10Code::findOrFail($id);
        return view('icd10.edit', compact('code'));
    }

    /**
     * Update ICD-10 code
     */
    public function update(Request $request, $id)
    {
        $code = ICD10Code::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:icd10_codes,code,' . $id,
            'display' => 'required|string|max:255'
        ]);

        // Update dengan simple structure
        $code->update([
            'code' => $validated['code'],
            'display' => $validated['display']
        ]);

        return redirect()->route('icd10.show', $id)
            ->with('success', 'Kode ICD-10 berhasil diperbarui.');
    }

    /**
     * Delete ICD-10 code
     */
    public function destroy($id)
    {
        $code = ICD10Code::findOrFail($id);
        $code->delete();

        return redirect()->route('icd10.index')
            ->with('success', 'Kode ICD-10 berhasil dihapus.');
    }

    /**
     * Search ICD-10 codes from Satu Sehat API
     */
    public function searchFromAPI(Request $request)
    {
        $query = $request->get('query');
        $limit = $request->get('limit', 20);

        if (empty($query)) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }

        try {
            $results = $this->satuSehatService->searchICD10($query, $limit);
            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch ICD-10 data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import ICD-10 codes from Satu Sehat API
     */
    public function importFromAPI(Request $request)
    {
        $validated = $request->validate([
            'codes' => 'required|array',
            'codes.*.code' => 'required|string',
            'codes.*.display' => 'required|string',
            'codes.*.definition' => 'nullable|string'
        ]);

        $imported = 0;
        $skipped = 0;

        foreach ($validated['codes'] as $codeData) {
            try {
                // Check if code already exists
                $existing = ICD10Code::where('code', $codeData['code'])->first();
                
                if (!$existing) {
                    ICD10Code::create([
                        'code' => strtoupper($codeData['code']),
                        'display' => $codeData['display'],
                        'definition' => $codeData['definition'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id()
                    ]);
                    $imported++;
                } else {
                    $skipped++;
                }
            } catch (\Exception $e) {
                \Log::error('Failed to import ICD-10 code: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'imported' => $imported,
            'skipped' => $skipped,
            'message' => "Berhasil mengimport {$imported} kode, {$skipped} kode dilewati karena sudah ada."
        ]);
    }
}
