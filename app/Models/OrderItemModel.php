<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemModel extends Model
{
    use HasFactory;
    
    // ឈ្មោះ Table ត្រូវឱ្យដូចក្នុង Database របស់លោកអ្នក (order_item)
    protected $table = 'order_items';
    
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * ទំនាក់ទំនងទៅកាន់ Order មេ
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderModel::class, 'order_id');
    }

    /**
     * ទំនាក់ទំនងទៅកាន់ Product ដើម្បីដឹងថាទំនិញនេះជាអ្វី (ឈ្មោះ, រូបភាព...)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }
}