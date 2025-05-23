<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
    ];

    /**
     * Get the courses for the category.
     */
    public function courses() 
    {
        return $this->hasMany(Course::class);
    }
}
