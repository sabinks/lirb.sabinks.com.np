<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'user_id'];

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($product) {
            $product->reviews->each->delete();
        });
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
