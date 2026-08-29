<?php

namespace App\Http\Controllers;

use App\Models\BalancePackage;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $package = BalancePackage::where('is_active', true)->first();

        // Fallback default package if DB not seeded yet
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
