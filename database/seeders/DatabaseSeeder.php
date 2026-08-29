<?php

namespace Database\Seeders;

use App\Models\BalancePackage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        BalancePackage::updateOrCreate(
            ['name' => 'ACCESS PASS'],
            [
                'virtual_balance' => 'unlimited simulated balance',
                'price' => 20.00,
                'currency' => 'USDT',
                'description' => 'Manual TRC20 payment verification required before access is granted.',
                'is_active' => true,
            ]
        );
    }
}
