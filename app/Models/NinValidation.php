<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NinValidation extends Model
{
      protected $fillable = [
        'user_id',
        'tnx_id',
        'refno',
        'nin_number',
        'description',
        'status',
        'reason',
        'refunded_at',
    ];
}
