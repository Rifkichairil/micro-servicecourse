<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyCourse extends Model
{
    use HasFactory;

    protected $table = 'my_courses';
    protected $cast = [
        'created_at' => 'datetime:y-m-d H:m:s',
        'updated_at' => 'datetime:y-m-d H:m:s',
    ];

    protected $fillable = [
        'course_id',
        'user_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }


}
