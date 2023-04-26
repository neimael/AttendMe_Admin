<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Presence;
use App\Models\AssignmentElevator;

class Elevator extends Model
{

    use HasFactory;
    protected $table='elevator';
    protected $primaryKey = 'id_elevator';
    protected $fillable = [
        'name',
        'qr_code',
        'id_location',
       
    ];
    public function presences()
    {
        return $this->hasMany(Presence::class,'id_elevator','id_elevator');
    }
    public function assignments()
    {
        return $this->hasMany(AssignmentElevator::class,'id_elevator','id_elevator');
    }
}
