<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'seller_id', 'name', 'description', 'price', 
        'stock_qty', 'category_id', 'supplier_id', 
        'image_url', 'status'
    ];

    // ភ្ជាប់ទៅ Category (អ្នកមានរួចហើយ)
    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id', 'id');
    }

    // បន្ថែមការភ្ជាប់ទៅ Supplier (ចំណុចថ្មី)
    public function supplier()
    {
        // យើងប្រើ belongsTo ព្រោះក្នុងតារាង products ជាអ្នកកាន់ supplier_id
        return $this->belongsTo(SupplyerModel::class, 'supplier_id', 'id');
    }
}