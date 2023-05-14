<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qrcodes extends Model
{
    use HasFactory;
    protected $table='qrcode';
    protected $primaryKey = 'id_qr_code';
    protected $fillable = [
        'mission',
        'qr_code',
        'id_elevator',
    ];
    public function elevator()
    {
        return $this->hasone(Elevator::class,'id_elevator','id_elevator');
    }
    public function assignments()
    {
        return $this->belongsTo(AssignmentElevator::class,'id_elevator','id_qr_code');
    }
    public function presences()
    {
        return $this->belongsTo(Presence::class,'id_elevator','id_qr_code');
    }
}
