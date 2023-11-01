<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Classify extends Model
{
    use HasFactory;
    public function classification(): HasOne
    {
        return $this->HasOne(ClassificationGroup::class, 'id', 'classification_group_id')->with('product');
    }
}
