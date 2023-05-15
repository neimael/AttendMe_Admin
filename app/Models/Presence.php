<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\qrcodes;


class Presence extends Model
{
    use HasFactory;
    protected $table='presence';
    protected $primaryKey = 'id_presence';
    protected $fillable = [
        'id_employee',
        'id_elevator',
        'check_in',
        'check_out',
        'attendance_day',
        'qrcode',
        'selfie',
    ];
    public function employee()
    {
        return $this->hasOne(User::class,'id','id_employee');
    }
    public function qrcodes()
    {
        return $this->hasOne(qrcodes::class,'id_qr_code','id_elevator');
    }
}


