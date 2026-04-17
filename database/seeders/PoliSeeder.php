<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Poli;

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $polis = [
            [
                'nama' => 'Poli Umum',
                'kode' => 'POL001',
                'deskripsi' => 'Poli untuk pemeriksaan kesehatan umum dan penyakit umum',
                'is_active' => true,
            ],
            [
                'nama' => 'Poli Gigi',
                'kode' => 'POL002',
                'deskripsi' => 'Poli untuk perawatan kesehatan gigi dan mulut',
                'is_active' => true,
            ],
            [
                'nama' => 'Poli Kandungan',
                'kode' => 'POL003',
                'deskripsi' => 'Poli untuk kesehatan reproduksi dan kehamilan',
                'is_active' => true,
            ],
            [
                'nama' => 'Poli Anak',
                'kode' => 'POL004',
                'deskripsi' => 'Poli untuk kesehatan anak dan balita',
                'is_active' => true,
            ],
            [
                'nama' => 'Poli Mata',
                'kode' => 'POL005',
                'deskripsi' => 'Poli untuk kesehatan mata dan penglihatan',
                'is_active' => true,
            ],
            [
                'nama' => 'Poli Jantung',
                'kode' => 'POL006',
                'deskripsi' => 'Poli untuk penyakit jantung dan pembuluh darah',
                'is_active' => true,
            ],
            [
                'nama' => 'Poli Saraf',
                'kode' => 'POL007',
                'deskripsi' => 'Poli untuk penyakit saraf dan gangguan neurologis',
                'is_active' => true,
            ],
            [
                'nama' => 'Poli Kulit',
                'kode' => 'POL008',
                'deskripsi' => 'Poli untuk penyakit kulit dan kelamin',
                'is_active' => true,
            ],
            [
                'nama' => 'Poli THT',
                'kode' => 'POL009',
                'deskripsi' => 'Poli untuk Telinga, Hidung, dan Tenggorokan',
                'is_active' => true,
            ],
            [
                'nama' => 'Poli Bedah',
                'kode' => 'POL010',
                'deskripsi' => 'Poli untuk konsultasi dan tindakan bedah',
                'is_active' => true,
            ],
        ];

        foreach ($polis as $poli) {
            Poli::firstOrCreate(
                ['kode' => $poli['kode']],
                $poli
            );
        }

        $this->command->info('Poli data seeded successfully!');
    }
}
