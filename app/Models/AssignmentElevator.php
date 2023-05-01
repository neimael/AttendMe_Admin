<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Elevator;

class AssignmentElevator extends Model
{
    use HasFactory;
    protected $table='assignment_elevator';
    protected $primaryKey = 'id_assignment_elevator';
    protected $fillable = [
        
        'id_employee',
        'id_elevator',
        'end_date',
        'start_date',
        'time_in',
        'time_out',

        
    ];
    public function employee()
    {
        return $this->belongsTo(User::class,'id','id_employee');
    }
    public function elevator()
    {
        return $this->belongsTo(Elevator::class,'id_elevator','id_elevator');
    }
}
