<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Presence;
use App\Models\AssignmentElevator;
use App\Models\SanitaryIssues;
use App\Models\PresenceRegulation;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table='employee';
    protected $primaryKey = 'id_employee';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function presences()
   {
       return $this->hasMany(Presence::class,'id_employee','id_employee');
   }
   public function assignments()
   {
       return $this->hasMany(AssignmentElevator::class,'id_employee','id_employee');
   }
   public function sanitaryIssues()
   {
       return $this->hasMany(SanitaryIssues::class,'id_employee','id_employee');
   }
   public function presenceRegulations()
   {
       return $this->hasMany(PresenceRegulation::class,'id_employee','id_employee');
   }

}
