<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    protected $table = 'trc_loan_request';
    protected $fillable = ['name','hp','email','guarantee','location','status','created_at'];
}
