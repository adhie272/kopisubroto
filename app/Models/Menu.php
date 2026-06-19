<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'image', 'description', 'category', 'is_active'];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        $image = trim((string) $this->image);

        if ($image === '') {
            return '/images/espresso.jpg';
        }

        $imagePath = str_starts_with($image, 'images/') ? $image : 'images/' . $image;

        if (! file_exists(public_path($imagePath))) {
            return '/images/espresso.jpg';
        }

        return '/' . $imagePath;
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope untuk filter kategori tertentu dengan urutan
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
