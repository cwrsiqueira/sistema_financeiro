<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entries extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'user_id',
        'transaction_type',
        'transaction_date',
        'transaction_description',
        'transaction_value',
    ];
}
