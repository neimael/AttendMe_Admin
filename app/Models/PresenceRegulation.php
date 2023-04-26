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
        'id_presence_regulation',
        'id_employee',
        'date',
        'time_in',
        'time_out',
        'status',
        'note',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class,'id_employee','id_employee');
    }
}
