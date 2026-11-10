<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const PAYMENT_CASH = 'cash';

    public const PAYMENT_TRANSFER = 'transfer';

    public const PAYMENT_QRIS = 'qris';

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const ORDER_PENDING = 'pending';

    public const ORDER_COOKING = 'cooking';

    public const ORDER_SERVED = 'served';

    protected $fillable = [
        'table_number',
        'items',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'paid_at',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'paid_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::STATUS_PAID;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed_at !== null;
    }

    public function isAwaitingConfirmation(): bool
    {
        return ! $this->isConfirmed();
    }

    public function statusLabel(): string
    {
        if (! $this->isConfirmed()) {
            return 'Menunggu Konfirmasi';
        }

        return match ($this->status) {
            self::ORDER_PENDING => 'Diproses',
            self::ORDER_COOKING => 'Dimasak',
            self::ORDER_SERVED => 'Disajikan',
            default => ucfirst($this->status),
        };
    }

    public function paymentMethodLabel(): string
    {
        return match ($this->payment_method) {
            self::PAYMENT_TRANSFER => 'Transfer',
            self::PAYMENT_QRIS => 'QRIS',
            default => 'Tunai',
        };
    }
}
