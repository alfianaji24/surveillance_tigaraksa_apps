<?php

namespace App\Imports;

use App\Models\DiagnosaPKM;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DiagnosaPKMCsvImport implements ToModel, WithHeadingRow, WithValidation
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

        // Skip kolom A (abaikan)
        return new DiagnosaPKM([
            'tanggal_kunjungan' => $this->convertDate($row['b'] ?? null), // B
            'poli' => $row['c'] ?? null, // C
            'no_rekam_medik' => $row['d'] ?? null, // D
            'nik' => $row['e'] ?? null, // E
            'nama_pasien' => $row['f'] ?? null, // F
            'alamat' => $row['g'] ?? null, // G
            'tanggal_lahir' => $this->convertDate($row['h'] ?? null), // H
            'umur' => $this->calculateUmur($row['h'] ?? null), // I (auto generate)
            'jenis_kelamin' => $row['j'] ?? null, // J
            'jenis_pasien' => $row['k'] ?? null, // K
            'no_bpjs' => ($row['k'] ?? null) === 'BPJS' ? ($row['l'] ?? null) : null, // L
            'jenis_bayar' => $row['m'] ?? null, // M
            'anamnesa' => $row['n'] ?? null, // N
            'diagnosa' => $row['o'] ?? null, // O
            'pemeriksa' => $row['p'] ?? null, // P
            'status' => $row['q'] ?? null, // Q
            'rs_rujukan' => ($row['q'] ?? null) === 'Rujuk' ? ($row['r'] ?? null) : null, // R
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
            'b', // tanggal_kunjungan
            'c', // poli
            'd', // no_rekam_medik
            'f', // nama_pasien
            'g', // alamat
            'h', // tanggal_lahir
            'j', // jenis_kelamin
            'k', // jenis_pasien
            'm', // jenis_bayar
            'o', // diagnosa
            'p', // pemeriksa
            'q', // status
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
            'b' => 'required', // tanggal_kunjungan
            'c' => 'required', // poli
            'd' => 'required|unique:diagnosa_p_k_m_s,no_rekam_medik', // no_rekam_medik
            'f' => 'required', // nama_pasien
            'g' => 'required', // alamat
            'h' => 'required', // tanggal_lahir
            'j' => 'required|in:L,P', // jenis_kelamin
            'k' => 'required', // jenis_pasien
            'm' => 'required', // jenis_bayar
            'o' => 'required', // diagnosa
            'p' => 'required', // pemeriksa
            'q' => 'required', // status
            'e' => 'nullable', // nik
            'l' => 'nullable', // no_bpjs
            'n' => 'nullable', // anamnesa
            'r' => 'nullable', // rs_rujukan
        ];
    }

    public function customValidationMessages()
    {
        return [
            'b.required' => 'Tanggal kunjungan harus diisi',
            'c.required' => 'Poli harus diisi',
            'd.required' => 'No. Rekam Medik harus diisi',
            'd.unique' => 'No. Rekam Medik sudah terdaftar di sistem',
            'f.required' => 'Nama pasien harus diisi',
            'g.required' => 'Alamat harus diisi',
            'h.required' => 'Tanggal lahir harus diisi',
            'j.required' => 'Jenis kelamin harus diisi',
            'j.in' => 'Jenis kelamin harus L atau P',
            'k.required' => 'Jenis pasien harus diisi',
            'm.required' => 'Jenis bayar harus diisi',
            'o.required' => 'Diagnosa harus diisi',
            'p.required' => 'Pemeriksa harus diisi',
            'q.required' => 'Status harus diisi',
        ];
    }
}
