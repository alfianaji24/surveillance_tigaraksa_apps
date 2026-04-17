<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\Poli;

class UpdatePasienPoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping poli string to poli_id
        $poliMapping = [
            'Umum' => 1,      // Poli Umum
            'Gigi' => 2,      // Poli Gigi
            'KIA' => 3,       // Poli Kandungan
            'Anak' => 4,      // Poli Anak
            'Mata' => 5,      // Poli Mata
            'Jantung' => 6,   // Poli Jantung
            'Saraf' => 7,     // Poli Saraf
            'Kulit' => 8,     // Poli Kulit
            'THT' => 9,       // Poli THT
            'Bedah' => 10,    // Poli Bedah
            'Lansia' => 1,    // Map Lansia to Poli Umum
        ];

        $updatedCount = 0;
        
        foreach ($poliMapping as $poliName => $poliId) {
            $count = Pasien::where('poli', $poliName)
                ->whereNull('poli_id')
                ->update(['poli_id' => $poliId]);
            
            $updatedCount += $count;
            
            if ($count > 0) {
                $this->command->info("Updated {$count} patients from '{$poliName}' to poli_id {$poliId}");
            }
        }

        $this->command->info("Total patients updated: {$updatedCount}");
        
        // Show remaining patients without poli_id
        $remainingCount = Pasien::whereNull('poli_id')->count();
        if ($remainingCount > 0) {
            $this->command->warn("Warning: {$remainingCount} patients still have null poli_id");
            
            // Show unique poli values that weren't mapped
            $unmappedPolis = Pasien::whereNull('poli_id')
                ->whereNotNull('poli')
                ->distinct()
                ->pluck('poli');
            
            if ($unmappedPolis->count() > 0) {
                $this->command->line("Unmapped poli values: " . $unmappedPolis->implode(', '));
            }
        }
    }
}
