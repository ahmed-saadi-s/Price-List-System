<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{   
    use HasFactory;

    // Disable timestamps
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];

    // one-to-many relationship between Country and PriceList.
    public function priceLists()
    {
        return $this->hasMany(PriceList::class, 'country_code', 'code');
    }
}
