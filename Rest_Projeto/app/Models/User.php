<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'ramo', // Adicionando o campo 'ramo' ao modelo
        'descricao',
        'tipo',
         // Adicionando o campo 'descricao' ao modelo
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    public function competencias()
    {
        return $this->belongsToMany(Competencia::class, 'competencia_user');
    }

    public function experiencia()
    {
        return $this->hasMany(Experiencia::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}


