<?php

namespace Database\Seeders;

use App\Models\Hazmat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HazmatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hazmats = [
            ['name' => 'Asbestos', 'table_type' => 'Unknown', 'color' => 'Blue'],
            ['name' => 'Polychlorinated Biphenyls (PCB)', 'table_type' => 'PCHM', 'color' => 'Green'],
            ['name' => 'Ozone depleting substances (ODS)', 'table_type' => 'Not Contained', 'color' => 'Brown'],
            ['name' => 'Organotin Compounds', 'table_type' => 'Contained', 'color' => 'Red'],
            ['name' => 'Cybutryne', 'table_type' => 'Unknown', 'color' => 'Black'],
            ['name' => 'Perfluorooctane Sulfonic Acid (PFOS)', 'table_type' => 'PCHM', 'color' => 'Orange'],
            ['name' => 'Cadmium (and compounds)', 'table_type' => 'Not Contained', 'color' => 'Blue'],
            ['name' => 'Hexavalent Chromium (and compounds)', 'table_type' => 'Contained', 'color' => 'Green'],
            ['name' => 'Lead (and compounds)', 'table_type' => 'Unknown', 'color' => 'Brown'],
            ['name' => 'Mercury (and compounds)', 'table_type' => 'PCHM', 'color' => 'Red'],
            ['name' => 'Polybrominated Biphenyls (PBB)', 'table_type' => 'Not Contained', 'color' => 'Black'],
            ['name' => 'Polybrominated Diphenyl Ethers (PBDE)', 'table_type' => 'Contained', 'color' => 'Orange'],
            ['name' => 'Polychloronaphthalenes (Cl≥3)', 'table_type' => 'Unknown', 'color' => 'Blue'],
            ['name' => 'Radioactive Substances', 'table_type' => 'PCHM', 'color' => 'Green'],
            ['name' => 'Certain Shortchain Chlorinated Paraffins', 'table_type' => 'Not Contained', 'color' => 'Brown'],
            ['name' => 'Hexabromocyclododecane (HBCDD)', 'table_type' => 'Contained', 'color' => 'Red']
        ];

        foreach ($hazmats as $hazmat) {
            Hazmat::firstOrCreate($hazmat);
        }
    }
}
