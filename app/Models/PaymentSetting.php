<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'qris_image',
        'bank_name',
        'account_number',
        'account_holder',
    ];

    public function hasRekening(): bool
    {
        return $this->bank_name !== null && $this->bank_name !== ''
            && $this->account_number !== null && $this->account_number !== '';
    }

    public function hasQris(): bool
    {
        return $this->qris_image !== null && $this->qris_image !== '';
    }
}
