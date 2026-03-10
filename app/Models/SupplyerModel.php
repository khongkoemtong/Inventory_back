<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyerModel extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'contact_name',
        'phone',
        'email',
        'address',
        'seller_id', // <--- ត្រូវតែថែមត្រង់នេះ ដើម្បីឱ្យ Admin ម្នាក់ៗមាន Supplier រៀងៗខ្លួន
    ];

    /**
     * ទាញរក Admin ដែលជាម្ចាស់ Supplier នេះ
     */
    public function seller()
    {
        return $this->belongsTo(UserModel::class, 'seller_id', 'id');
    }

    /**
     * បន្ថែមការភ្ជាប់ទំនាក់ទំនង (Relationship) ទៅកាន់ Product
     */
    public function products()
    {
        return $this->hasMany(ProductModel::class, 'supplier_id', 'id');
    }
}