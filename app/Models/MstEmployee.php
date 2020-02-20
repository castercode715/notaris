<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MstEmployee extends Authenticatable
{
    use Notifiable;

    const ACTIVE    = 1;
    const INACTIVE  = 0;

    protected $table = "mst_employee"; 

    protected $primaryKey = 'id';

    protected $fillable = [
        'email', 
        'username', 
        'password', 
        'ip_address', 
        'full_name', 
        'gender', 
        'birth_place', 
        'birth_date', 
        'address', 
        'villages_id', 
        'zip_code', 
        'phone', 
        'photo', 
        'active', 
        'created_by', 
        'updated_by', 
        'deleted_at', 
        'deleted_by', 
        'is_deleted',
        'role_id'
    ];

    protected $hidden = ['password',  'remember_token'];

    public function role()
    {
        return $this->belongsTo('App\Models\SecRole');
    }
}
