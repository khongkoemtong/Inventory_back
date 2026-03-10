<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;

    protected $table = 'role'; // ឈ្មោះតារាងក្នុង DB របស់ប្អូន
    protected $fillable = ['name'];

    // ទំនាក់ទំនង៖ Role មួយ មាន User ច្រើន (Has Many)
    public function users()
    {
        return $this->hasMany(UserModel::class, 'role_id', 'id');
    }
}