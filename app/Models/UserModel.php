<?php

namespace App\Models;

// ១. ត្រូវប្តូរមកប្រើ Authenticatable របស់ Laravel ប្រព័ន្ធ Login វិញ
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// ២. ប្តូរពី extends Authenticate មកជា extends Authenticatable
class UserModel extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable;

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

    public function role() 
    {
        return $this->belongsTo(RoleModel::class, 'role_id', 'id');
    }

    public function order() 
    {
        return $this->hasMany(OrderModel::class, 'user_id', 'id');
    }
}