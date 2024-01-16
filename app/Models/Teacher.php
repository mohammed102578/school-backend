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

class Teacher



extends Model implements JWTSubject, AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;


    protected $fillable = [
        'password','email','name_ar','name_en','specialization_id','image','attachment',
        'gender_id','Joining_Date','address','National_ID','Blood_Type_id','Nationality_id','Religion_id','bank_account','phone'
    ];
    protected $table = 'teachers';
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



  // علاقة بين المعلمين والتخصصات لجلب اسم التخصص
  public function specialization()
  {
      return $this->belongsTo('App\Models\Specialization', 'specialization_id');
  }

  // علاقة بين المعلمين والانواع لجلب جنس المعلم
  public function gender()
  {
      return $this->belongsTo('App\Models\Gender', 'gender_id');
  }

  public function nationality()
  {
      return $this->belongsTo('App\Models\Nationalitie', 'Nationality_id');
  }
  public function blood()
    {
        return $this->belongsTo('App\Models\Type_Blood', 'Blood_Type_id');
    }

    public function religion()
    {
        return $this->belongsTo('App\Models\Religion', 'Religion_id');
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
