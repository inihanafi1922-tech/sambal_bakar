<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffSchedule extends Model
{
    protected $fillable = ['user_id', 'name', 'duty_date', 'shift', 'note'];

    protected function casts(): array
    {
        return [
            'duty_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function displayName(): string
    {
        return $this->name ?: ($this->user?->name ?? 'Staf dihapus');
    }

    public function displayRole(): string
    {
        if ($this->name) {
            return 'Penjaga Kasir';
        }

        return ucfirst($this->user?->role ?? '-');
    }
}
