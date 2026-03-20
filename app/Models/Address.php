<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'postal_code',
        'prefecture',
        'city',
        'address_line',
        'phone_number',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
