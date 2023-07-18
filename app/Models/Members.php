<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Members extends Model
{
    use HasFactory;
    use HasApiTokens, Notifiable;

    protected $table = 'table_member';

    protected $fillable = [
        'fullname', 'username', 'email', 'password',
    ];


    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
