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
    protected $fillable = [
    
        'first_name',
        'last_name',
        'email',
        'phone_number',  
        'adress',
        'password',
        'avatar',
        'cin',
        'birthday',
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
       return $this->hasMany(Presence::class,'id_employee','id'); 
      }
   public function assignments()
   {        return $this->hasMany(AssignmentElevator::class,'id_employee','id');
   }    public function sanitaryIssues()
   {
        return $this->hasMany(SanitaryIssues::class,'id_employee','id');
   }
    public function presenceRegulations()
   {
        return $this->belongsTo(PresenceRegulation::class,'id_employee','id');
   }

}
