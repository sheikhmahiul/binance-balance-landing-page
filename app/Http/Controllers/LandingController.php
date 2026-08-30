<?php

namespace App\Http\Controllers;

use App\Models\BalancePackage;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $package = null;

        try {
            $package = BalancePackage::where('is_active', true)->first();
        } catch (\Throwable $e) {
            // DB connection or table missing, use default fallback package
        }

        // Fallback default package if DB query fails or not seeded yet
        if (!$package) {
            $package = (object) [
                'id' => 1,
                'name' => 'ACCESS PASS',
                'virtual_balance' => 'unlimited simulated balance',
                'price' => 20.00,
                'currency' => 'USDT',
                'description' => 'Manual TRC20 payment verification required before access is granted.',
                'is_active' => true,
            ];
        }

        return view('landing', compact('package'));
    }

}
