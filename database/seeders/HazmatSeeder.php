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
            ['name' => 'Asbestos', 'short_name' => 'Asb', 'table_type' => 'A-1', 'color' => 'Blue'],
            ['name' => 'Polychlorinated Biphenyls (PCB)', 'short_name' => 'PCB', 'table_type' => 'A-2', 'color' => 'Green'],
            ['name' => 'Ozone depleting substances (ODS)', 'short_name' => 'ODS', 'table_type' => 'A-3', 'color' => 'Brown'],
            ['name' => 'Organotin Compounds', 'short_name' => 'Otin', 'table_type' => 'A-4', 'color' => 'Red'],
            ['name' => 'Cybutryne', 'short_name' => 'Cyb', 'table_type' => 'A-5', 'color' => 'Black'],
            ['name' => 'Perfluorooctane Sulfonic Acid (PFOS)', 'short_name' => 'PFOS', 'table_type' => 'A-6', 'color' => 'Orange'],
            ['name' => 'Cadmium (and compounds)', 'short_name' => 'Cd', 'table_type' => 'B-1', 'color' => 'Blue'],
            ['name' => 'Chromium (and compounds)', 'short_name' => 'Cr', 'table_type' => 'B-2', 'color' => 'Green'],
            ['name' => 'Lead (and compounds)', 'short_name' => 'Pb', 'table_type' => 'B-3', 'color' => 'Brown'],
            ['name' => 'Mercury (and compounds)', 'short_name' => 'Hg', 'table_type' => 'B-4', 'color' => 'Red'],
            ['name' => 'Polybrominated Biphenyls (PBB)', 'short_name' => 'PBB', 'table_type' => 'B-5', 'color' => 'Black'],
            ['name' => 'Polybrominated Diphenyl Ethers (PBDE)', 'short_name' => 'PBDE', 'table_type' => 'B-6', 'color' => 'Orange'],
            ['name' => 'Polychloronaphthalenes (Cl≥3)', 'short_name' => 'PCN', 'table_type' => 'B-7', 'color' => 'Blue'],
            ['name' => 'Radioactive Material', 'short_name' => 'Radio', 'table_type' => 'B-8', 'color' => 'Green'],
            ['name' => 'Certain Shortchain Chlorinated Paraffins', 'short_name' => 'SCCP', 'table_type' => 'B-9', 'color' => 'Brown'],
            ['name' => 'Hexabromocyclododecane (HBCDD)', 'short_name' => 'HBCDD', 'table_type' => 'B-10', 'color' => 'Red']
        ];

        foreach ($hazmats as $hazmat) {
            Hazmat::updateOrCreate(["name" => $hazmat['name']], $hazmat);
        }
    }
}
