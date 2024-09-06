<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
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

    public function area() : BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'employee_role', 'employee_id', 'role_id');
    }

    public function scopeSearch($query, $search)
    {
        // Aplica un filtro de búsqueda sobre el query, buscando coincidencias parciales en varias columnas
        return $query->where('id', 'LIKE', '%' . $search . '%') // Busca en la columna 'id'
        ->orWhere('name', 'LIKE', '%' . $search . '%') // Busca en la columna 'name'
        ->orWhere('email', 'LIKE', '%' . $search . '%') // Busca en la columna 'email'
        ->orWhere('sex', 'LIKE', '%' . $search . '%') // Busca en la columna 'sex'
        ->orWhereHas('area',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like',  '%' . $search . '%'); // Busca en la columna 'name' de la relacion de area
            }
        )
        ->orWhere('description', 'LIKE', '%' . $search . '%'); // Busca en la columna 'description'
    }
}
