<?php

namespace App\Models;

use App\Models\ProductUnit;
use App\Models\ProductGallery;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'purchase_price',
        'selling_price',
        'packaging',
        'margin',
        'discount',
        'stock',
        'tags',
        'categories_id',
        'units_id',
    ];

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class, 'products_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'categories_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(ProductUnit::class, 'units_id', 'id');
    }
}
