<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'total',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
