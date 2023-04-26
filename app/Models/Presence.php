<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Elevator;


class Presence extends Model
{
    use HasFactory;
    protected $table='presence';
    protected $primaryKey = 'id_presence';
    protected $fillable = [
        'id_employee',
        'id_elevator',
        'date',
        'check_in',
        'check_out',
        'attendance_day',
        'qrcode',
        'status',
        'selfie',
    ];
    public function employee()
    {
        return $this->belongsTo(User::class,'id_employee','id_employee');
    }
    public function elevator()
    {
        return $this->belongsTo(Elevator::class,'id_elevator','id_elevator');
    }

}
