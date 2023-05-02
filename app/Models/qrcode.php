<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qrcode extends Model
{
    use HasFactory;
    protected $table='qrcode';
    protected $primaryKey = 'id_qr_code';
    protected $fillable = [
        'mission',
        'qr_code',
        
    
    ];
    public function elevator()
    {
        return $this->belongsTo(Elevator::class,'id_qr_code');
    }
}
