<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'file',
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
        return $this->belongsToMany(Product::class, ProductImage::getTableName());
    }

    /**
     * Get Full Url
     */
    public function getFullUrlAttribute(): string
    {
        return asset($this->file);
    }
}
