<?php

namespace App\Http\Controllers;

use App\Models\DiagnosaPKM;
use App\Imports\DiagnosaPKMImport;
use App\Imports\DiagnosaPKMCsvImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DiagnosaPKMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diagnosa = DiagnosaPKM::latest()->paginate(20);
        return view('diagnosa.index', compact('diagnosa'));
    }

    /**
     * Show the form for importing data.
     */
    public function import()
    {
        // Check if ZipArchive is available
        $zipArchiveAvailable = class_exists('ZipArchive');
        
        return view('diagnosa.import', compact('zipArchiveAvailable'));
    }

    /**
     * Process the imported Excel/CSV file.
     */
    public function import_proses(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            // Check if ZipArchive is available for Excel files
            if (in_array($extension, ['xlsx', 'xls']) && !class_exists('ZipArchive')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Extension PHP ZipArchive tidak terinstall. Silakan gunakan file CSV atau install extension ZipArchive terlebih dahulu.'
                ], 500);
            }
            
            // Use appropriate import class
            if ($extension === 'csv') {
                Excel::import(new DiagnosaPKMCsvImport, $file);
            } else {
                Excel::import(new DiagnosaPKMImport, $file);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Data diagnosa PKM berhasil diimport'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download template Excel.
     */
    public function download_template()
    {
        $templatePath = public_path('template_pkm/Template_Data_Diagnosa_PKM.xlsx');
        
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        return response()->download($templatePath, 'Template_Data_Diagnosa_PKM.xlsx');
    }

    /**
     * Display the specified resource.
     */
    public function show(DiagnosaPKM $diagnosaPKM)
    {
        return view('diagnosa.show', compact('diagnosaPKM'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DiagnosaPKM $diagnosaPKM)
    {
        try {
            $diagnosaPKM->delete();
            return redirect()->route('diagnosa-pkm.index')
                ->with('success', 'Data diagnosa berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('diagnosa-pkm.index')
                ->with('error', 'Gagal menghapus data diagnosa: ' . $e->getMessage());
        }
    }
}
