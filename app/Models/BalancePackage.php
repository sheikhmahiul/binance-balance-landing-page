<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BalancePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'virtual_balance',
        'price',
        'currency',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $table = $this->hasMany(Order::class);
    }
}
