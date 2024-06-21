<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public const PUBLISHED = 'published';
    public const NOT_PUBLISHED = 'not_published';

    protected $fillable = ['product_id', 'review', 'publish'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
