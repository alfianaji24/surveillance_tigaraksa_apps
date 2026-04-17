<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SurvailancePenyakit;

class SurvailancePenyakitSeeder extends Seeder
{
    public function run()
    {
        $penyakit = [
            [
                'kode' => 'DA',
                'nama_penyakit' => 'Diare Akut',
                'icd10_codes' => ['A09', 'K59.1'],
                'kategori' => 'Gastrointestinal',
                'aktif' => true,
            ],
            [
                'kode' => 'MK',
                'nama_penyakit' => 'Malaria Konfirmasi',
                'icd10_codes' => ['B50', 'B51', 'B52', 'B53', 'B54'],
                'kategori' => 'Parasitic',
                'aktif' => true,
            ],
            [
                'kode' => 'DD',
                'nama_penyakit' => 'Suspek Demam Dengue',
                'icd10_codes' => ['A91', 'A90'],
                'kategori' => 'Viral',
                'aktif' => true,
            ],
            [
                'kode' => 'PN',
                'nama_penyakit' => 'Pneumonia',
                'icd10_codes' => ['J12', 'J13', 'J14', 'J15', 'J16', 'J17', 'J18'],
                'kategori' => 'Respiratory',
                'aktif' => true,
            ],
            [
                'kode' => 'DB',
                'nama_penyakit' => 'Diare Berdarah/ Dysenteriae (Shigellosis)',
                'icd10_codes' => ['A03'],
                'kategori' => 'Gastrointestinal',
                'aktif' => true,
            ],
            [
                'kode' => 'DT',
                'nama_penyakit' => 'Suspek Demam Tifoid',
                'icd10_codes' => ['A01.0'],
                'kategori' => 'Bacterial',
                'aktif' => true,
            ],
            [
                'kode' => 'SJ',
                'nama_penyakit' => 'Sindrom Jaundis Akut/ Yellow Fever',
                'icd10_codes' => ['A95', 'A98.0', 'A98.1'],
                'kategori' => 'Viral',
                'aktif' => true,
            ],
            [
                'kode' => 'CK',
                'nama_penyakit' => 'Suspek Chikungunya',
                'icd10_codes' => ['A92.0'],
                'kategori' => 'Viral',
                'aktif' => true,
            ],
            [
                'kode' => 'FB',
                'nama_penyakit' => 'Suspek Flu Burung pada Manusia',
                'icd10_codes' => ['J09', 'J10', 'J11'],
                'kategori' => 'Viral',
                'aktif' => true,
            ],
            [
                'kode' => 'CM',
                'nama_penyakit' => 'Suspek Campak',
                'icd10_codes' => ['B05'],
                'kategori' => 'Viral',
                'aktif' => true,
            ],
            [
                'kode' => 'DP',
                'nama_penyakit' => 'Kasus Observasi Diphtheria',
                'icd10_codes' => ['A36'],
                'kategori' => 'Bacterial',
                'aktif' => true,
            ],
            [
                'kode' => 'PT',
                'nama_penyakit' => 'Suspek Pertusis / Whooping Cough',
                'icd10_codes' => ['A37'],
                'kategori' => 'Bacterial',
                'aktif' => true,
            ],
            [
                'kode' => 'AFP',
                'nama_penyakit' => 'Acute Flaccid Paralysis (Lumpuh Layuh Mendadak)',
                'icd10_codes' => ['G61.0', 'G83.2'],
                'kategori' => 'Neurological',
                'aktif' => true,
            ],
            [
                'kode' => 'GHPR',
                'nama_penyakit' => 'Kasus GHPR (Gigitan Hewan Menular Rabies)',
                'icd10_codes' => ['W54', 'W55', 'W56', 'W57', 'W58', 'W59', 'W64'],
                'kategori' => 'Zoonotic',
                'aktif' => true,
            ],
            [
                'kode' => 'AN',
                'nama_penyakit' => 'Suspek Anthrax',
                'icd10_codes' => ['A22'],
                'kategori' => 'Bacterial',
                'aktif' => true,
            ],
            [
                'kode' => 'LP',
                'nama_penyakit' => 'Suspek Leptospirosis',
                'icd10_codes' => ['A27'],
                'kategori' => 'Bacterial',
                'aktif' => true,
            ],
            [
                'kode' => 'CH',
                'nama_penyakit' => 'Suspek Cholera',
                'icd10_codes' => ['A00'],
                'kategori' => 'Bacterial',
                'aktif' => true,
            ],
            [
                'kode' => 'ME',
                'nama_penyakit' => 'Suspek Meningitis/ Encephalitis',
                'icd10_codes' => ['G00', 'G01', 'G02', 'G03', 'G04'],
                'kategori' => 'Neurological',
                'aktif' => true,
            ],
            [
                'kode' => 'JE',
                'nama_penyakit' => 'Japanese Encephalitis',
                'icd10_codes' => ['A83.0'],
                'kategori' => 'Viral',
                'aktif' => true,
            ],
            [
                'kode' => 'TN',
                'nama_penyakit' => 'Suspek Tetanus Neonatum',
                'icd10_codes' => ['A33'],
                'kategori' => 'Bacterial',
                'aktif' => true,
            ],
            [
                'kode' => 'TT',
                'nama_penyakit' => 'Suspek Tetanus',
                'icd10_codes' => ['A34', 'A35'],
                'kategori' => 'Bacterial',
                'aktif' => true,
            ],
            [
                'kode' => 'ILI',
                'nama_penyakit' => 'ILI (Influenza Like Illness)',
                'icd10_codes' => ['J11.1', 'J22'],
                'kategori' => 'Respiratory',
                'aktif' => true,
            ],
            [
                'kode' => 'HFMD',
                'nama_penyakit' => 'Suspek HFMD (Hand, Foot, and Mouth Disease)',
                'icd10_codes' => ['B08.4'],
                'kategori' => 'Viral',
                'aktif' => true,
            ],
            [
                'kode' => 'ISPA',
                'nama_penyakit' => 'ISPA',
                'icd10_codes' => ['J00', 'J01', 'J02', 'J03', 'J04', 'J05', 'J06', 'J20', 'J21', 'J22'],
                'kategori' => 'Respiratory',
                'aktif' => true,
            ],
            [
                'kode' => 'COVID',
                'nama_penyakit' => 'Covid-19 Konfirmasi',
                'icd10_codes' => ['U07.1', 'U07.2'],
                'kategori' => 'Viral',
                'aktif' => true,
            ],
        ];

        foreach ($penyakit as $data) {
            SurvailancePenyakit::create($data);
        }
    }
}
