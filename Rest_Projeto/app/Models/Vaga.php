<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaga extends Model
{
    use HasFactory;

    protected $fillable = [
        'ramo_id',
        'titulo',
        'descricao',
        'competencias',
        'experiencia',
        'salario_min',
        'salario_max',
        'ativo',
        'empresa_id'
    ];

    protected $casts = [
        'competencias' => 'array',
        'salario_min' => 'decimal:2',
        'salario_max' => 'decimal:2',
        'ativo' => 'boolean'
    ];

    public function ramo()
    {
        return $this->belongsTo(Ramo::class);
    }

    public function empresa()
    {
        return $this->belongsTo(User::class, 'empresa_id');
    }

    public function competencias()
    {
        return $this->belongsToMany(Competencia::class, 'vaga_competencia', 'vaga_id', 'competencia_id');
    }
}
