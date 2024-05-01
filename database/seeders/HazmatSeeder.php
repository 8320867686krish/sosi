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
            ['name' => 'Asbestos', 'short_name'=> 'Asb', 'table_type' => 'table a', 'color' => 'Blue'],
            ['name' => 'Polychlorinated Biphenyls (PCB)', 'short_name'=> 'PCB', 'table_type' => 'table a', 'color' => 'Green'],
            ['name' => 'Ozone depleting substances (ODS)', 'short_name'=> 'ODS', 'table_type' => 'table a', 'color' => 'Brown'],
            ['name' => 'Organotin Compounds', 'short_name'=> 'OC', 'table_type' => 'table a', 'color' => 'Red'],
            ['name' => 'Cybutryne', 'short_name'=> 'CY', 'table_type' => 'table a', 'color' => 'Black'],
            ['name' => 'Perfluorooctane Sulfonic Acid (PFOS)', 'short_name'=> 'PFOS', 'table_type' => 'table a (eu)', 'color' => 'Orange'],
            ['name' => 'Cadmium (and compounds)', 'short_name'=> 'CD', 'table_type' => 'table b', 'color' => 'Blue'],
            ['name' => 'Hexavalent Chromium (and compounds)', 'short_name'=> 'CR', 'table_type' => 'table b', 'color' => 'Green'],
            ['name' => 'Lead (and compounds)', 'short_name'=> 'LD', 'table_type' => 'table b', 'color' => 'Brown'],
            ['name' => 'Mercury (and compounds)', 'short_name'=> 'HG', 'table_type' => 'table b', 'color' => 'Red'],
            ['name' => 'Polybrominated Biphenyls (PBB)', 'short_name'=> 'PBB', 'table_type' => 'table b', 'color' => 'Black'],
            ['name' => 'Polybrominated Diphenyl Ethers (PBDE)', 'short_name'=> 'PBDE', 'table_type' => 'table b', 'color' => 'Orange'],
            ['name' => 'Polychloronaphthalenes (Cl≥3)', 'short_name'=> 'Cl≥3', 'table_type' => 'table b', 'color' => 'Blue'],
            ['name' => 'Radioactive Substances', 'short_name'=> 'RS', 'table_type' => 'table b', 'color' => 'Green'],
            ['name' => 'Certain Shortchain Chlorinated Paraffins', 'short_name'=> 'CSCP', 'table_type' => 'table b', 'color' => 'Brown'],
            ['name' => 'Hexabromocyclododecane (HBCDD)', 'short_name'=> 'HBCDD', 'table_type' => 'table b (eu)', 'color' => 'Red']
        ];

        foreach ($hazmats as $hazmat) {
            Hazmat::updateOrCreate(["name" => $hazmat['name']],$hazmat);
        }
    }
}
