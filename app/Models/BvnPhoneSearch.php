<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BvnPhoneSearch extends Model
{
    protected $fillable = [
        'user_id',
        'tnx_id',
        'refno',
        'phone_number',
        'name',
        'status',
        'reason',
        'refunded_at',
    ];
}
