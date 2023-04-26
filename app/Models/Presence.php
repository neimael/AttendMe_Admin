<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Presence extends Model
{
    use HasFactory;
    protected $table='presence';
    protected $primaryKey = 'id_presence';
    protected $fillable = [
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
