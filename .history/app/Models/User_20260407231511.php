<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];
}