<?php

namespace App\Models;

use App\Models\User;
use App\Models\ProductImage;
use App\Models\ProductColor;
use App\Models\ProductComment;
use App\Models\Orderitem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'product_user_id',
        'name',
        'slug',
        'brand',
        'small_description',
        'description',
        'original_price',
        'selling_price',
        'quantity',
        'trending',
        'featured',
        'status',
        'meta_title',
        'meta_keyword',
        'meta_description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function productColors()
    {
        return $this->hasMany(ProductColor::class, 'product_id', 'id');

    }
    public function productUser()
    {
        return $this->belongsTo(User::class, 'product_user_id', 'id');

    }

    public function confirmComment()
    {
        return $this->belongsTo(Orderitem::class, 'product_id', 'id');
        
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class, 'product_id', 'id');
    }
}
