<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    use HasFactory;
    protected $table ='payment';
    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'transection_id',
        'payment_date',
        'status',
    ];

    public function order() 
     {
        return $this->belongsTo(OrderModel::class,'order_id','id');
        
    }
}
