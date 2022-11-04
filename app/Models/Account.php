<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = [
        'created_at', 'deleted_at', 'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function scopeBlocked($query)
    {
        return $query->where('blocked', true);
    }

    public function scopeActive($query)
    {
        return $query->where('blocked', false);
    }

    public function blockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blocked_by', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cif', 'cif');
    }

    public function debitTransactions()
    {
        return $this->hasMany(Transaction::class, 'debit_account', 'account_id');
    }
}
