<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'enable',
    ];

    protected $dateFormat = 'U';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get all categories
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, CategoryProduct::getTableName());
    }

    /**
     * Get all images
     */
    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, ProductImage::getTableName());
    }
}
