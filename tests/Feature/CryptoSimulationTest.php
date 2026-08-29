<?php

namespace Tests\Feature;

use App\Models\BalancePackage;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CryptoSimulationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed default package
        BalancePackage::create([
            'name' => 'ACCESS PASS',
            'virtual_balance' => 'unlimited simulated balance',
            'price' => 20.00,
            'currency' => 'USDT',
            'description' => 'Manual TRC20 payment verification required before access is granted.',
            'is_active' => true,
        ]);
    }

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertSee('Activate Your');
        $response->assertSee('Binance Virtual Live Trading Balance');
        $response->assertSee('ACCESS PASS');




    }

    public function test_checkout_page_renders(): void
    {
        $package = BalancePackage::first();

        $response = $this->get("/checkout/{$package->id}");

        $response->assertStatus(200);
        $response->assertSee('Selected Package');
        $response->assertSee('ACCESS PASS');
        $response->assertSee('Customer Order Details');
    }

    public function test_checkout_form_submission_creates_order_and_redirects_to_payment(): void
    {
        $package = BalancePackage::first();

        $response = $this->post("/checkout/{$package->id}", [
            'full_name' => 'Satoshi Nakamoto',
            'email' => 'satoshi@example.com',
            'telegram_username' => 'satoshi_trade',
        ]);

        $this->assertDatabaseHas('orders', [
            'full_name' => 'Satoshi Nakamoto',
            'email' => 'satoshi@example.com',
            'telegram_username' => 'satoshi_trade',
            'amount' => 20.00,
            'currency' => 'USDT',
            'payment_network' => 'TRC20',
            'payment_status' => 'pending',
            'verification_status' => 'waiting',
        ]);

        $order = Order::first();
        $response->assertRedirect("/payment/{$order->order_number}");
    }

    public function test_payment_page_displays_trc20_address_and_telegram_button(): void
    {
        $package = BalancePackage::first();

        $order = Order::create([
            'order_number' => 'ORD-TEST1234',
            'balance_package_id' => $package->id,
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'telegram_username' => 'testuser',
            'amount' => 20.00,
            'currency' => 'USDT',
            'payment_network' => 'TRC20',
            'payment_address' => 'TQG2Ry4k9N9tF1dYR1T9Hs1H4stDZ8mtyi',
            'payment_status' => 'pending',
            'verification_status' => 'waiting',
        ]);

        $response = $this->get("/payment/{$order->order_number}");

        $response->assertStatus(200);
        $response->assertSee('ORD-TEST1234');
        $response->assertSee('TQG2Ry4k9N9tF1dYR1T9Hs1H4stDZ8mtyi');
        $response->assertSee('Open Telegram Support');
        $response->assertSee('TRC20 network');

    }

    public function test_payment_confirmation_updates_status_to_under_review(): void
    {
        $package = BalancePackage::first();

        $order = Order::create([
            'order_number' => 'ORD-TEST5678',
            'balance_package_id' => $package->id,
            'full_name' => 'Test User 2',
            'email' => 'test2@example.com',
            'telegram_username' => 'testuser2',
            'amount' => 20.00,
            'currency' => 'USDT',
            'payment_network' => 'TRC20',
            'payment_address' => 'T9xZ2kQ5L8vM7nW3yP1rS6tU4vX8zW2y1A',
            'payment_status' => 'pending',
            'verification_status' => 'waiting',
        ]);

        $response = $this->post("/payment/{$order->order_number}/confirm", [
            'transaction_ref' => '0xabcdef123456789',
        ]);

        $response->assertRedirect("/order/{$order->order_number}");

        $this->assertDatabaseHas('orders', [
            'order_number' => 'ORD-TEST5678',
            'payment_status' => 'submitted',
            'verification_status' => 'under_review',
            'transaction_ref' => '0xabcdef123456789',
        ]);
    }

    public function test_admin_can_approve_order(): void
    {
        $package = BalancePackage::first();

        $order = Order::create([
            'order_number' => 'ORD-ADMIN999',
            'balance_package_id' => $package->id,
            'full_name' => 'Admin Test',
            'email' => 'admin_test@example.com',
            'telegram_username' => 'admintest',
            'amount' => 20.00,
            'currency' => 'USDT',
            'payment_network' => 'TRC20',
            'payment_address' => 'T9xZ2kQ5L8vM7nW3yP1rS6tU4vX8zW2y1A',
            'payment_status' => 'submitted',
            'verification_status' => 'under_review',
        ]);

        // Authenticate admin session
        $this->withSession(['admin_authenticated' => true]);

        $response = $this->post("/admin/orders/{$order->id}/status", [
            'verification_status' => 'approved',
            'payment_status' => 'verified',
            'transaction_ref' => 'TXID-VERIFIED-777',
        ]);

        $response->assertRedirect('/admin');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'verification_status' => 'approved',
            'payment_status' => 'verified',
            'transaction_ref' => 'TXID-VERIFIED-777',
        ]);
    }
}
