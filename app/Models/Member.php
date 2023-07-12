<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'fullname', 'username', 'email', 'password',
    ];

    protected $hidden = [
        'password',
    ];
}
