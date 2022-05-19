<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $table = 'chapters';

    protected $cast = [
        'created_at' => 'datetime:y-m-d H:m:s',
        'updated_at' => 'datetime:y-m-d H:m:s',
    ];


    protected $fillable = [
        'name',
        'course_id'
    ];

    public function lesson()
    {
        return $this->hasMany(Lesson::class)->orderBy('id', 'ASC');
    }
}
