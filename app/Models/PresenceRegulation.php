<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PresenceRegulation extends Model
{
    use HasFactory;
    protected $table='presence_regulation';
    protected $primaryKey = 'id_presence_regulation';
    protected $fillable = [
        'id_employee',
        'attendance_day',
        'check_in',
        'check_out',
        'status',
        'issue_type',
        'report',
        
        
    ];


    public function employee()
    {
        return $this->hasOne(User::class,'id','id_employee');
    }
}
