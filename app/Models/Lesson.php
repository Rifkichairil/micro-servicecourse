<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';
    protected $cast = [
        'created_at' => 'datetime:y-m-d H:m:s',
        'updated_at' => 'datetime:y-m-d H:m:s',
    ];


    protected $fillable = [
        'name',
        'video',
        'chapter_id'
    ];

}
