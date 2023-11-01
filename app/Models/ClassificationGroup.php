<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClassificationGroup extends Model
{
    use HasFactory;
    public function classify(): HasMany
    {
        return $this->HasMany(Classify::class, 'classification_group_id', 'id');
    }
    public function product(): HasOne
    {
        return $this->HasOne(Product::class, 'id', 'product_id');
    }
}
