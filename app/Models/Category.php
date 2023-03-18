<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'enable',
    ];

    protected $dateFormat = 'U';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get all products
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, CategoryProduct::getTableName());
    }

    /**
     * Relation to category products
     */
    public function categoryProducts(): HasMany
    {
        return $this->hasMany(CategoryProduct::class, 'category_id');
    }
}
