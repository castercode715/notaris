<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VInvestmentModels extends Model
{
    protected $table = 'v_investment';

    public $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;
}
