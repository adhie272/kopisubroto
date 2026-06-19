<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nomor_meja', 'customer_name', 'total_price', 'status'];

    /**
     * Relasi ke Order Items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Transaction
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Scope filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
