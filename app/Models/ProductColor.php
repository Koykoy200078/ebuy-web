<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Color;

class ProductColor extends Model
{
    use HasFactory;
    protected $table = 'product_colors';

    protected $fillable = [
        'protect_id',
        'color_id',
        'quantity',
    ];

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }
}

