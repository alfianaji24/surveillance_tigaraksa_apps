<?php

namespace App\Imports;

use App\Models\DiagnosaPKM;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DiagnosaPKMImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Cek apakah baris memiliki data atau kosong
        if ($this->isRowEmpty($row)) {
            return null; // Skip baris kosong
        }

        // Use header names from Excel file
        return new DiagnosaPKM([
            'tanggal_kunjungan' => $this->convertDate($row['tanggal_kunjungan'] ?? null),
            'poli' => $row['poli'] ?? null,
            'no_rekam_medik' => $row['no_rekam_medik'] ?? null,
            'nik' => $row['nik'] ?? null,
            'nama_pasien' => $row['nama_pasien'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'tanggal_lahir' => $this->convertDate($row['tanggal_lahir'] ?? null),
            'umur' => $this->calculateUmur($row['tanggal_lahir'] ?? null), // Auto generate
            'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
            'jenis_pasien' => $row['jenis_pasien'] ?? null,
            'no_bpjs' => (strtolower($row['jenis_pasien'] ?? '') === 'bpjs') ? ($row['no_bpjs'] ?? null) : null,
            'jenis_bayar' => $row['jenis_bayar'] ?? null,
            'anamnesa' => $row['anamnesa'] ?? null,
            'diagnosa' => $row['diagnosa'] ?? null,
            'pemeriksa' => $row['pemeriksa'] ?? null,
            'status' => $row['status'] ?? null,
            'rs_rujukan' => (strtolower($row['status'] ?? '') === 'dirujuk') ? ($row['rs_rujukan'] ?? null) : null,
        ]);
    }

    /**
     * Konversi format tanggal dari Excel ke format Y-m-d
     */
    private function convertDate($dateValue)
    {
        if (empty($dateValue)) {
            return null;
        }

        // Debug: Log format tanggal yang diterima
        Log::info('Date value received: ' . $dateValue . ' (Type: ' . gettype($dateValue) . ')');

        // Jika sudah dalam format Y-m-d, langsung return
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateValue)) {
            return $dateValue;
        }

        // Coba berbagai format yang mungkin dari Excel
        $formats = [
            'Y-m-d',      // 1993-10-01
            'd/m/Y',      // 01/10/1993
            'd-m-Y',      // 01-10-1993
            'm/d/Y',      // 10/01/1993
            'Y/m/d',      // 1993/10/01
            'd.m.Y',      // 01.10.1993
            'Y.m.d',      // 1993.10.01
        ];

        foreach ($formats as $format) {
            try {
                $carbon = Carbon::createFromFormat($format, $dateValue);
                if ($carbon) {
                    return $carbon->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Lanjut ke format berikutnya
                continue;
            }
        }

        // Jika semua format gagal, coba parse umum
        try {
            $carbon = Carbon::parse($dateValue);
            return $carbon->format('Y-m-d');
        } catch (\Exception $e) {
            // Coba handle Excel serial number (jika tanggal dikirim sebagai angka)
            if (is_numeric($dateValue)) {
                try {
                    // Excel serial number: 1 = 1900-01-01, 2 = 1900-01-02, dst
                    $excelDate = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($dateValue - 2);
                    return $excelDate->format('Y-m-d');
                } catch (\Exception $e2) {
                    Log::error('Failed to convert Excel serial date: ' . $dateValue);
                }
            }

            // Jika masih gagal, return null untuk menghindari error
            Log::error('Failed to convert date: ' . $dateValue . ' - ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Hitung umur dari tanggal lahir
     */
    private function calculateUmur($tanggalLahir)
    {
        if (empty($tanggalLahir)) {
            return null;
        }

        try {
            $date = $this->convertDate($tanggalLahir);
            if ($date) {
                return Carbon::parse($date)->age;
            }
        } catch (\Exception $e) {
            Log::error('Failed to calculate age: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Cek apakah baris kosong atau tidak
     */
    private function isRowEmpty(array $row)
    {
        // Field wajib yang harus ada untuk dianggap baris tidak kosong
        $requiredFields = [
            'tanggal_kunjungan',
            'poli',
            'no_rekam_medik',
            'nama_pasien',
            'alamat',
            'tanggal_lahir',
            'jenis_kelamin',
            'jenis_pasien',
            'jenis_bayar',
            'diagnosa',
            'pemeriksa',
            'status',
        ];

        // Cek apakah semua field wajib kosong atau tidak ada
        foreach ($requiredFields as $field) {
            if (!empty($row[$field]) && trim($row[$field]) !== '') {
                return false; // Ada data, baris tidak kosong
            }
        }

        return true; // Semua field kosong, baris kosong
    }

    public function rules(): array
    {
        return [
            'tanggal_kunjungan' => 'required',
            'poli' => 'required',
            'no_rekam_medik' => 'required', // Remove unique constraint
            'nama_pasien' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'jenis_pasien' => 'required',
            'jenis_bayar' => 'required',
            'diagnosa' => 'nullable', // Make optional for empty diagnosa
            'pemeriksa' => 'required',
            'status' => 'required',
            'nik' => 'nullable',
            'no_bpjs' => 'nullable',
            'anamnesa' => 'nullable',
            'rs_rujukan' => 'nullable',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'tanggal_kunjungan.required' => 'Tanggal kunjungan harus diisi',
            'poli.required' => 'Poli harus diisi',
            'no_rekam_medik.required' => 'No. Rekam Medik harus diisi',
            'nama_pasien.required' => 'Nama pasien harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'jenis_pasien.required' => 'Jenis pasien harus diisi',
            'jenis_bayar.required' => 'Jenis bayar harus diisi',
            'pemeriksa.required' => 'Pemeriksa harus diisi',
            'status.required' => 'Status harus diisi',
        ];
    }
}
