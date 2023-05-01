<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SanitaryIssues extends Model
{
    use HasFactory;
    
    protected $table='sanitary_regulation';
    protected $primaryKey = 'id_sanitary_regulation';
    protected $fillable = [
        
        'id_employee',
        'regulation_date',
        'certificate',
        'report'
        
    ];

    public function employee()
    {
        return $this->belongsTo(User::class,'id','id_employee');
    }
}
