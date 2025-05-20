<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'image_file_name',
        'visibility',
        'publish_date',
        'user_id',
        'category_id',
        'is_free'
    ];
    
    /**
     * Get the category that owns the course.
     */
    public function category() 
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the lessons for the course.
     */
    public function lessons() 
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Get the user that imparts the course.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the given user is the owner of the course.
     *
     * @param int $userId The ID of the user to check.
     * @return bool
     */
    public function isOwner($userId)
    {
        return $this->user_id === $userId;
    }
}
