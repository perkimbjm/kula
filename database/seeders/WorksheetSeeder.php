<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Worksheet;

class WorksheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['menu' => 'KONTRAK FISIK', 'icon' => 'heroicon-o-document-text'],
            ['menu' => 'PEMBAYARAN FISIK', 'icon' => 'heroicon-o-currency-dollar'],
            ['menu' => 'PEMILIHAN FISIK', 'icon' => 'heroicon-o-check-circle'],
            ['menu' => 'KONTRAK PRC', 'icon' => 'heroicon-o-document-text'],
            ['menu' => 'PEMILIHAN PRC', 'icon' => 'heroicon-o-check-circle'],
            ['menu' => 'PEMBAYARAN PRC', 'icon' => 'heroicon-o-currency-dollar'],
            ['menu' => 'HPS', 'icon' => 'heroicon-o-calculator'],
            ['menu' => 'KONTRAK KONS PRC', 'icon' => 'heroicon-o-document-text'],
            ['menu' => 'PEMILIHAN KONS PRC', 'icon' => 'heroicon-o-check-circle'],
            ['menu' => 'PEMBAYARAN KONS PRC', 'icon' => 'heroicon-o-currency-dollar'],
            ['menu' => 'PHO', 'icon' => 'heroicon-o-clipboard-document-check'],
            ['menu' => 'KONTRAK PWS', 'icon' => 'heroicon-o-document-text'],
            ['menu' => 'PEMILIHAN PWS', 'icon' => 'heroicon-o-check-circle'],
            ['menu' => 'PEMBAYARAN PWS', 'icon' => 'heroicon-o-currency-dollar'],
            ['menu' => 'PPK', 'icon' => 'heroicon-o-user'],
            ['menu' => 'KONTRAK KONS PWS', 'icon' => 'heroicon-o-document-text'],
            ['menu' => 'PEMILIHAN KONS PWS', 'icon' => 'heroicon-o-check-circle'],
            ['menu' => 'PEMBAYARAN KONS PWS', 'icon' => 'heroicon-o-currency-dollar'],
        ];

        foreach ($menus as $item) {
            Worksheet::create([
                'menu' => $item['menu'],
                'url' => '#',
                'icon' => $item['icon'],
            ]);
        }
    }
}
