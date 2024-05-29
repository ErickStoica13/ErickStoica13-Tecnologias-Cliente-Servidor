<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experiencia extends Model
{
    protected $table = 'experiencia'; // Adicione esta linha para especificar o nome da tabela
    
    protected $fillable = [
        'nome_empresa',
        'inicio',
        'fim',
        'cargo',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}