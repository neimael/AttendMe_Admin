<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SanitaryIssues extends Model
{
    use HasFactory;
    
    protected $table='sanitary_issues';
    protected $primaryKey = 'id_sanitary_issue';
    protected $fillable = [
        'id_sanitary_issue',
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
