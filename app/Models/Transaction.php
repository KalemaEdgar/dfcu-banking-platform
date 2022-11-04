<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'cif', 'debit_account', 'credit_account', 'transaction_type', 'amount', 'status', 'reason', 'created_by', 'reference', 'client_ip', 'recipient_name', 'description'
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cif', 'cif');
    }
}
