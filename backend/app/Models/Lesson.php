<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_lesson')
            ->withPivot('order')
            ->withTimestamps();
    }

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}