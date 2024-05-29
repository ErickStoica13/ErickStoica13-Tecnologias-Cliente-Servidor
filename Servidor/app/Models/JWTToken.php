<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JWTToken extends Model
{
    use HasFactory;

    protected $table = 'jwt_tokens';

    protected $fillable = [
        'token',
    ];
}
