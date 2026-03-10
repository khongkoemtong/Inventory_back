<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name', 
        'description', 
        'image', 
        'seller_id', // អនុញ្ញាតឱ្យរក្សាទុក ID របស់ Admin
    ];

    // ទាញរកព័ត៌មាន Admin ដែលជាម្ចាស់ Category នេះ
    public function seller()
    {
        return $this->belongsTo(UserModel::class, 'seller_id', 'id');
    }

    // ទាញរក Products ទាំងអស់ដែលស្ថិតក្នុង Category នេះ
    public function products()
    {
        return $this->hasMany(ProductModel::class, 'category_id', 'id');
    }
}