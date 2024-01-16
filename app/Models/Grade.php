<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Grade extends Model
{


    protected $fillable=['name_ar','name_en','notes'];
    protected $table = 'Grades';
    public $timestamps = true;

    // علاقة المراحل الدراسية لجلب الاقسام المتعلقة بكل مرحلة
    public function Sections()
    {

        return $this->hasMany('App\Models\Section', 'Grade_id');
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
