<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SanitaryIssues extends Model
{
    use HasFactory;
    
    protected $table='sanitary_regulation';
    protected $primaryKey = 'id_sanitary';
    protected $fillable = [
        
        'id_employee',
        'report',
        'certificate',
        
    ];


    public function employee()
    {
        return $this->hasOne(User::class,'id','id_employee');
    }
}
