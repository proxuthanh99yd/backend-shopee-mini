<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;
    use Searchable;

    public function searchable(): bool
    {
        return $this->name;
    }

    public function thumbnails(): HasMany
    {
        return $this->hasMany(Thumbnail::class, 'product_id', 'id');
    }

    public function categories(): HasOne
    {
        return $this->HasOne(Category::class, 'id', 'category_id');
    }

    public function brands(): HasOne
    {
        return $this->HasOne(Brand::class, 'id', 'brand_id');
    }

    public function classification(): HasMany
    {
        return $this->HasMany(ClassificationGroup::class, 'product_id', 'id')->with('classify');
    }

    public function classify(): HasManyThrough
    {
        return $this->hasManyThrough(
            Classify::class,
            ClassificationGroup::class,
            'product_id', // Foreign key on the owners table...
            'classification_group_id', // Foreign key on the cars table...
            'id', // Local key on the mechanics table...
            'id' // Local key on the cars table...
        );
    }
}
