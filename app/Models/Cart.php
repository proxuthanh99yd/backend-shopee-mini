<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    use HasFactory;
    public function product(): HasOne
    {
        return $this->HasOne(Product::class, 'id', 'product_id');
    }
    public function classify(): HasOne
    {
        return $this->HasOne(Classify::class, 'id', 'classify_id');
    }
}
