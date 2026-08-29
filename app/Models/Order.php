<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'balance_package_id',
        'full_name',
        'email',
        'telegram_username',
        'amount',
        'currency',
        'payment_network',
        'payment_address',
        'payment_status',
        'verification_status',
        'transaction_ref',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(BalancePackage::class, 'balance_package_id');
    }

    public function getFormattedStatusAttribute(): string
    {
        return match ($this->verification_status) {
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'under_review' => 'Under Review',
            default => 'Waiting Verification',
        };
    }

    public function getTelegramUrlAttribute(): string
    {
        $handle = config('services.telegram.username', 'Binance_Balance_4U');
        $handle = ltrim($handle, '@');
        $message = rawurlencode("Hello! Here is my payment proof for Order #" . $this->order_number . ":\n- Sender USDT ID/Address:\n- Attached Screenshot:");
        
        return "https://t.me/" . $handle . "?text=" . $message;
    }
}
