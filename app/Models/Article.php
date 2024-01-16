<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;


    protected $table = 'articles';

    protected $fillable = [
        'title','author','body','created_at','updated_at'
    ];






    

}
?>