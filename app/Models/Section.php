<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Section extends Model
{
    protected $fillable=['name_section_en','name_section_ar','grade_id','classroom_id','status'];

    protected $table = 'sections';
    public $timestamps = true;

    public function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($date)
    {
        return  Carbon::parse($this->attributes['updated_at'])->format('Y-m-d');
    }

    // علاقة بين الاقسام والصفوف لجلب اسم الصف في جدول الاقسام

    public function My_classs()
    {
        return $this->belongsTo('App\Models\Classroom', 'classroom_id');
    }

    // علاقة الاقسام مع المعلمين
    public function teachers()
    {
        return $this->belongsToMany('App\Models\Teacher','teacher_section');
    }

    public function Grades()
    {
        return $this->belongsTo('App\Models\Grade','grade_id');
    }

}
