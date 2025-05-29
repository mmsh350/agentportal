<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = [
        'idno',
        'type',
        'nin',
        'first_name',
        'middle_name',
        'last_name',
        'phoneno',
        'email',
        'dob',
        'gender',
        'address',
        'enrollment_branch',
        'enrollment_bank',
        'photo',
        'registration_date',
        'title',
        'state',
        'lga',
        'trackingId',
    ];
}
