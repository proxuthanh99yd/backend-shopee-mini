<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;

class Order extends Model
{
    use HasFactory;
    public function order_items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id')->with('classify:id,name,price','product:id,name,image,discount');
    }
    public function classify(): HasOne
    {
        return $this->HasOne(Classify::class, 'id', 'classify_id');
    }
    public function product(): HasOne
    {
        return $this->HasOne(Product::class, 'id', 'product_id');
    }
    public function user(): HasOne
    {
        return $this->HasOne(User::class, 'id', 'user_id');
    }
}
