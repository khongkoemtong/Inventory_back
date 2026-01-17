<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'seller_id',
        'name',
        'description',
        'price',
        'stock_qty',
        'category_id',
        'supplier_id',
        'image_url',
        'status',
    ];
    public function orderItems() {
    return $this->hasMany(OrderModel::class);
}



}
