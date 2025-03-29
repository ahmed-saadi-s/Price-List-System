<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceList extends Model
{
    use HasFactory;

    // Disable timestamps
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'country_code', 'currency_code', 'price',
        'start_date', 'end_date', 'priority'
    ];

    // one-to-many relationship between Product and PriceList.
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // one-to-many relationship between Country and PriceList.
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    // one-to-many relationship between Currency and PriceList.
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
}
