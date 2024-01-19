<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoCaja extends Model
{
    use HasFactory;

    protected $fillable = ['fecha_apertura', 'fecha_cierre', 'usuario_id', 'monto_inicial', 'monto_final', 'estado', 'comentario'];


    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Agrega la relación con las ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'turno_caja_id');
    }


}
