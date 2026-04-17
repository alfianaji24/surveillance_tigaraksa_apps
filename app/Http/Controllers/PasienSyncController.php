<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\DiagnosaPKM;
use App\Models\Icd10Code;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PasienSyncController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-pasien')->only(['sync', 'syncAll']);
    }

    /**
     * Show sync dashboard
     */
    public function index()
    {
        // Get statistics
        $totalDiagnosaPkm = DiagnosaPKM::count();
        $totalPasien = Pasien::count();
        $uniquePatients = DiagnosaPKM::distinct('no_rekam_medik')->count('no_rekam_medik');
        $syncedPatients = Pasien::whereIn('no_rekam_medik', function($query) {
            $query->select('no_rekam_medik')->from('diagnosa_p_k_m_s');
        })->count();

        return view('pasien.sync', compact(
            'totalDiagnosaPkm', 
            'totalPasien', 
            'uniquePatients', 
            'syncedPatients'
        ));
    }

    /**
     * Sync single patient from DiagnosaPKM to Pasien
     */
    public function sync($noRekamMedik)
    {
        try {
            // Get latest visit from DiagnosaPKM
            $latestVisit = DiagnosaPKM::where('no_rekam_medik', $noRekamMedik)
                ->latest('tanggal_kunjungan')
                ->first();

            if (!$latestVisit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pasien tidak ditemukan di DiagnosaPKM'
                ], 404);
            }

            // Check if patient already exists
            $existingPasien = Pasien::where('no_rekam_medik', $noRekamMedik)->first();

            if ($existingPasien) {
                // Update existing patient
                $pasien = $this->updatePasienFromDiagnosa($existingPasien, $latestVisit);
                $message = 'Data pasien berhasil diperbarui';
            } else {
                // Create new patient
                $pasien = $this->createPasienFromDiagnosa($latestVisit);
                $message = 'Data pasien berhasil ditambahkan ke bank data';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $pasien
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync all patients from DiagnosaPKM to Pasien
     */
    public function syncAll(Request $request)
    {
        try {
            $syncType = $request->get('sync_type', 'latest_only'); // latest_only, all_visits, update_existing
            
            // Get unique patients from DiagnosaPKM
            $uniquePatients = DiagnosaPKM::select('no_rekam_medik')
                ->distinct()
                ->pluck('no_rekam_medik');

            $syncedCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($uniquePatients as $noRekamMedik) {
                try {
                    // Get latest visit
                    $latestVisit = DiagnosaPKM::where('no_rekam_medik', $noRekamMedik)
                        ->latest('tanggal_kunjungan')
                        ->first();

                    if (!$latestVisit) continue;

                    $existingPasien = Pasien::where('no_rekam_medik', $noRekamMedik)->first();

                    if ($existingPasien) {
                        if ($syncType === 'update_existing') {
                            $this->updatePasienFromDiagnosa($existingPasien, $latestVisit);
                            $updatedCount++;
                        } else {
                            $skippedCount++;
                        }
                    } else {
                        $this->createPasienFromDiagnosa($latestVisit);
                        $syncedCount++;
                    }

                } catch (\Exception $e) {
                    $errors[] = "RM $noRekamMedik: " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Sinkronisasi selesai! $syncedCount ditambahkan, $updatedCount diperbarui, $skippedCount dilewati.",
                'synced' => $syncedCount,
                'updated' => $updatedCount,
                'skipped' => $skippedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new Pasien from DiagnosaPKM data
     */
    private function createPasienFromDiagnosa($diagnosa)
    {
        // Find poli by name
        $poli = \App\Models\Poli::where('nama', 'like', '%' . $diagnosa->poli . '%')->first();
        $poliId = $poli ? $poli->id : null;

        $pasien = Pasien::create([
            'tanggal_kunjungan' => $diagnosa->tanggal_kunjungan,
            'poli' => $diagnosa->poli,
            'poli_id' => $poliId,
            'no_rekam_medik' => $diagnosa->no_rekam_medik,
            'nik' => $diagnosa->nik,
            'no_bpjs' => $diagnosa->no_bpjs,
            'nama_pasien' => $diagnosa->nama_pasien,
            'alamat' => $diagnosa->alamat,
            'no_hp' => null, // Not available in DiagnosaPKM
            'tanggal_lahir' => $diagnosa->tanggal_lahir,
            'umur' => $diagnosa->umur . ' tahun',
            'jenis_kelamin' => $diagnosa->jenis_kelamin,
            'jenis_pasien' => $diagnosa->jenis_pasien,
            'jenis_bayar' => $diagnosa->jenis_bayar,
            'anamnesa' => $diagnosa->anamnesa,
            'diagnosa' => $diagnosa->diagnosa,
            'pemeriksa' => $diagnosa->pemeriksa,
            'status' => $diagnosa->status,
            'status_active' => true,
            'rs_rujukan' => $diagnosa->rs_rujukan
        ]);

        // Process ICD10 codes
        if ($diagnosa->kode_icd_10) {
            $icd10 = Icd10Code::where('code', $diagnosa->kode_icd_10)->first();
            if ($icd10) {
                $pasien->icd10Codes()->attach($icd10->id);
            }
        }

        return $pasien;
    }

    /**
     * Update existing Pasien with latest DiagnosaPKM data
     */
    private function updatePasienFromDiagnosa($pasien, $diagnosa)
    {
        // Find poli by name
        $poli = \App\Models\Poli::where('nama', 'like', '%' . $diagnosa->poli . '%')->first();
        $poliId = $poli ? $poli->id : null;

        $pasien->update([
            'tanggal_kunjungan' => $diagnosa->tanggal_kunjungan,
            'poli' => $diagnosa->poli,
            'poli_id' => $poliId,
            'nik' => $diagnosa->nik,
            'no_bpjs' => $diagnosa->no_bpjs,
            'nama_pasien' => $diagnosa->nama_pasien,
            'alamat' => $diagnosa->alamat,
            'tanggal_lahir' => $diagnosa->tanggal_lahir,
            'umur' => $diagnosa->umur . ' tahun',
            'jenis_kelamin' => $diagnosa->jenis_kelamin,
            'jenis_pasien' => $diagnosa->jenis_pasien,
            'jenis_bayar' => $diagnosa->jenis_bayar,
            'anamnesa' => $diagnosa->anamnesa,
            'diagnosa' => $diagnosa->diagnosa,
            'pemeriksa' => $diagnosa->pemeriksa,
            'status' => $diagnosa->status,
            'rs_rujukan' => $diagnosa->rs_rujukan
        ]);

        // Update ICD10 codes
        $pasien->icd10Codes()->detach();
        if ($diagnosa->kode_icd_10) {
            $icd10 = Icd10Code::where('code', $diagnosa->kode_icd_10)->first();
            if ($icd10) {
                $pasien->icd10Codes()->attach($icd10->id);
            }
        }

        return $pasien;
    }

    /**
     * Get patients that can be synced
     */
    public function getSyncablePatients(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = DiagnosaPKM::select('no_rekam_medik', 'nama_pasien', 'poli', 'tanggal_kunjungan')
            ->selectRaw('COUNT(*) as total_visits')
            ->selectRaw('MAX(tanggal_kunjungan) as latest_visit')
            ->groupBy('no_rekam_medik', 'nama_pasien', 'poli')
            ->orderBy('latest_visit', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('no_rekam_medik', 'like', "%{$search}%");
            });
        }

        $patients = $query->paginate(20);

        // Check if already synced
        $patients->getCollection()->transform(function ($patient) {
            $patient->is_synced = Pasien::where('no_rekam_medik', $patient->no_rekam_medik)->exists();
            return $patient;
        });

        return response()->json($patients);
    }

    /**
     * Delete synced patient
     */
    public function deleteSynced($noRekamMedik)
    {
        try {
            $pasien = Pasien::where('no_rekam_medik', $noRekamMedik)->first();
            
            if (!$pasien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pasien tidak ditemukan di bank data'
                ], 404);
            }

            $pasien->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil dihapus dari bank data'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
