<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'quantity', 'price', 'session_id'];

    /**
     * Relasi ke Menu
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Hitung total harga item
     */
    public function getTotalPrice()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Scope untuk filter berdasarkan session
     */
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }
}
