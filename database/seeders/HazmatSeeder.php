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
            ['name' => 'Asbestos', 'short_name' => 'Asb', 'table_type' => 'A-1', 'color' => '#2B35AF'],
            ['name' => 'Polychlorinated Biphenyls (PCB)', 'short_name' => 'PCB', 'table_type' => 'A-2', 'color' => '#9F2B68'],
            ['name' => 'Ozone depleting substances (ODS)', 'short_name' => 'ODS', 'table_type' => 'A-3', 'color' => '#FDDA0D'],
            ['name' => 'Organotin Compounds', 'short_name' => 'Otin', 'table_type' => 'A-4', 'color' => '#98FB98'],
            ['name' => 'Cybutryne', 'short_name' => 'Cyb', 'table_type' => 'A-5', 'color' => '#BF0A30'],
            ['name' => 'Perfluorooctane Sulfonic Acid (PFOS)', 'short_name' => 'PFOS', 'table_type' => 'A-6', 'color' => '#F89880'],
            ['name' => 'Cadmium (and compounds)', 'short_name' => 'Cd', 'table_type' => 'B-1', 'color' => '#924444'],
            ['name' => 'Chromium (and compounds)', 'short_name' => 'Cr', 'table_type' => 'B-2', 'color' => '#7C4700'],
            ['name' => 'Lead (and compounds)', 'short_name' => 'Pb', 'table_type' => 'B-3', 'color' => '#808080'],
            ['name' => 'Mercury (and compounds)', 'short_name' => 'Hg', 'table_type' => 'B-4', 'color' => '#E6E6FA'],
            ['name' => 'Polybrominated Biphenyls (PBB)', 'short_name' => 'PBB', 'table_type' => 'B-5', 'color' => '#91ffff'],
            ['name' => 'Polybrominated Diphenyl Ethers (PBDE)', 'short_name' => 'PBDE', 'table_type' => 'B-6', 'color' => '#8B008B'],
            ['name' => 'Polychlorinated Naphthalenes (Cl≥3)', 'short_name' => 'PCN', 'table_type' => 'B-7', 'color' => '#BFFF00'],
            ['name' => 'Radioactive Material', 'short_name' => 'Radio', 'table_type' => 'B-8', 'color' => '#006A4E'],
            ['name' => 'Certain Shortchain Chlorinated Paraffins', 'short_name' => 'SCCP', 'table_type' => 'B-9', 'color' => '#FFD1DC'],
            ['name' => 'Hexabromocyclododecane (HBCDD)', 'short_name' => 'HBCDD', 'table_type' => 'B-10', 'color' => '#008080']
        ];

        foreach ($hazmats as $hazmat) {
            Hazmat::updateOrCreate(["name" => $hazmat['name']], $hazmat);
        }
    }
}
