<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function teachers()
    {
        return $this->users()->whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        });
    }

    public function students()
    {
        return $this->users()->whereHas('roles', function ($query) {
            $query->where('name', 'student');
        });
    }
        public function documents()
    {
        return $this->hasMany(Document::class);
    }
}