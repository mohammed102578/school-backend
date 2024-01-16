<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;

class Classroom extends Model
{



    protected $table = 'Classrooms';
    public $timestamps = true;
    protected $fillable=['name_en','name_ar','grade_id'];


    // علاقة بين الصفوف المراحل الدراسية لجلب اسم المرحلة في جدول الصفوف

    public function Grades()
    {
        return $this->belongsTo('App\Models\Grade', 'grade_id');
    }

    public function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($date)
    {
        return  Carbon::parse($this->attributes['updated_at'])->format('Y-m-d');
    }

}
