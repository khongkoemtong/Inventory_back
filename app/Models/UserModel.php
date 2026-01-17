<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'role_id',
        'phone',
        'status',
        'image',
    ];
    public function role (){
        return $this->belongsTo(RoleModel::class ,'role_id','id');
    }
    public function order (){
        return $this ->hasMany(OrderModel::class,'user_id','id');
    }
}
