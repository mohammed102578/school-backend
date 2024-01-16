<?php

namespace App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class My_Parent extends Model implements JWTSubject, AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;


    protected $fillable = [
        'password','email','Name_Father_ar','Name_Father_en','National_ID_Father','image','attachment',
        'Passport_ID_Father','Phone_Father','Job_Father','Nationality_Father_id','Blood_Type_Father_id'
        ,'Religion_Father_id','Address_Father','Name_Mother_ar','Name_Mother_en','National_ID_Mother','Passport_ID_Mother',
        'Phone_Mother','Job_Mother','Nationality_Mother_id','Blood_Type_Mother_id','Religion_Mother_id',
        'Address_Mother'
    ];
    protected $table = 'my__parents';
    protected $guarded=[];


    protected $hidden = [
        'password',
    ];


    public function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($date)
    {
        return  Carbon::parse($this->attributes['updated_at'])->format('Y-m-d');
    }


    public function setPasswordAttribute($value)
    {
        $this->attributes['password']=Hash::make($value);
    }

    public function nationality_father()
    {
        return $this->belongsTo('App\Models\Nationalitie', 'Nationality_Father_id');
    }

    public function nationality_mother()
    {
        return $this->belongsTo('App\Models\Nationalitie', 'Nationality_Mother_id');
    }

    public function religion_father()
    {
        return $this->belongsTo('App\Models\Religion', 'Religion_Father_id');
    }

    public function religion_mother()
    {
        return $this->belongsTo('App\Models\Religion', 'Religion_Mother_id');
    }

    public function blood_father()
    {
        return $this->belongsTo('App\Models\Type_Blood', 'Blood_Type_Father_id');
    }

    public function blood_mother()
    {
        return $this->belongsTo('App\Models\Type_Blood', 'Blood_Type_Mother_id');
    }

    public function student()
    {
        return $this->hasMany('App\Models\Student', 'parent_id');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
