<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'base_price',
        'description'
    ];

    // one-to-many relationship between Product and PriceList.
    public function priceLists()
    {
        return $this->hasMany(PriceList::class);
    }
}
