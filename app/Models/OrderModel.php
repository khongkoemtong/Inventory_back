<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderModel extends Model
{
    use HasFactory;
    
    // ត្រូវប្រាកដថាឈ្មោះ Table ក្នុង Database គឺ 'orders' (ជាទូទៅ Laravel ប្រើពហុវចនៈ)
    protected $table = 'orders'; 
    
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'order_date',
    ];

    // ទំនាក់ទំនងទៅកាន់ទំនិញលម្អិត (Order Items)
    public function items(): HasMany
    {
        // កែពី OrderModel::class ទៅ OrderItemModel::class
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }

    // ទំនាក់ទំនងទៅកាន់ការបង់ប្រាក់ (Payment)
    public function payment(): HasOne
    {
        return $this->hasOne(PaymentModel::class, 'order_id');
    }

    // ទំនាក់ទំនងទៅកាន់អ្នកទិញ (User)
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}