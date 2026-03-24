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
    
    protected $table = 'orders'; 
    
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'order_date',
        'customer_name', // បន្ថែមសម្រាប់ភ្ញៀវមកហាង (Guest)
        'phone',         // បន្ថែមសម្រាប់ភ្ញៀវមកហាង (Guest)
    ];

    // ទំនាក់ទំនងទៅកាន់ទំនិញលម្អិត
    public function items(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }

    // ទំនាក់ទំនងទៅកាន់ការបង់ប្រាក់
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