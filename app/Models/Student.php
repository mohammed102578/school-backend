<?php

namespace App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class Student extends Model implements JWTSubject, AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword , SoftDeletes;



    protected $fillable = [
        'name_en','image','attachment','name_ar','email','password','gender_id','nationalitie_id',
        'blood_id','Date_Birth','Grade_id','Classroom_id','section_id'
        ,'parent_id','academic_year','deleted_at',
    ];
    protected $table = 'students';
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

    public function blood()
    {
        return $this->belongsTo('App\Models\Type_Blood', 'blood_id');
    }

      // علاقة بين الطلاب والانواع لجلب اسم النوع في جدول الطلاب

    public function gender()
    {
        return $this->belongsTo('App\Models\Gender', 'gender_id');
    }

    // علاقة بين الطلاب والمراحل الدراسية لجلب اسم المرحلة في جدول الطلاب

    public function grade()
    {
        return $this->belongsTo('App\Models\Grade', 'Grade_id');
    }


    // علاقة بين الطلاب الصفوف الدراسية لجلب اسم الصف في جدول الطلاب

    public function classroom()
    {
        return $this->belongsTo('App\Models\Classroom', 'Classroom_id');
    }

    // علاقة بين الطلاب الاقسام الدراسية لجلب اسم القسم  في جدول الطلاب

    public function section()
    {
        return $this->belongsTo('App\Models\Section', 'section_id');
    }


    // علاقة بين الطلاب والصور لجلب اسم الصور  في جدول الطلاب
    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    // علاقة بين الطلاب والجنسيات  لجلب اسم الجنسية  في جدول الجنسيات

    public function nationality()
    {
        return $this->belongsTo('App\Models\Nationalitie', 'nationalitie_id');
    }


    // علاقة بين الطلاب والاباء لجلب اسم الاب في جدول الاباء

    public function parent()
    {
        return $this->belongsTo('App\Models\My_Parent', 'parent_id');
    }

    // علاقة بين جدول سدادت الطلاب وجدول الطلاب لجلب اجمالي المدفوعات والمتبقي
    public function student_account()
    {
        return $this->hasMany('App\Models\StudentAccount', 'student_id');
    }

   // علاقة بين جدول الطلاب وجدول الحضور والغياب
    public function attendance()
    {
        return $this->hasMany('App\Models\Attendance', 'student_id');
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
