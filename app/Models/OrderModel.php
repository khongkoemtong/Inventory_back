<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderModel extends Model
{
    use HasFactory;
    protected $table = 'order';
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'order_date',
    ];

    public function items()
    {
        return $this->hasMany(OrderModel::class);
    }
    public function payment()
    {
        return $this->hasOne(PaymentModel::class);
    }
    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }
}
