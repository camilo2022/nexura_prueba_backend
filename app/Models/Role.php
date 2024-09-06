<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name'
    ];

    public function scopeSearch($query, $search)
    {
        // Aplica un filtro de búsqueda sobre el query, buscando coincidencias parciales en varias columnas
        return $query->where('id', 'LIKE', '%' . $search . '%') // Busca en la columna 'id'
        ->orWhere('name', 'LIKE', '%' . $search . '%'); // Busca en la columna 'name'
    }
}
