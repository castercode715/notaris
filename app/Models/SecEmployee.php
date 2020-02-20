<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SecEmployee extends Authenticatable
{
    use Notifiable;

    const ACTIVE    = 1;
    const INACTIVE  = 0;

    protected $table = "sec_employee"; 

    protected $primaryKey = 'sec_employee_id';

    protected $fillable = ['sec_employee_id', 'email', 'username', 'password'];

    protected $hidden = ['password',  'remember_token'];
}
