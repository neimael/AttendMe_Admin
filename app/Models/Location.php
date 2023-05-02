<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table='location';
    protected $primaryKey = 'id_location';
    protected $fillable = [
        'longitude',
        'latitude',
        'region',
        'ville',
       
    ];
    public function elevator()
    {
        return $this->belongsTo(Elevator::class,'id_location');
    }
}
