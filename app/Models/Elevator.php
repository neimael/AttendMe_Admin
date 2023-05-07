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
        'id_qr_code',
        'id_location',
        
        
       
    ];
    public function presences()
    {
        return $this->hasMany(Presence::class,'id_elevator','id_elevator');
    }
   
    public function location()
    {
        return $this->hasOne(Location::class,'id_location','id_location');
    }

    public function qrcode()
    {
        return $this->belongsTo(qrcodes::class,'id_elevator','id_elevator');
    }
}
