<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    /**
     * Get the events associated with the price.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the menu associated with the price.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the range associated with the price.
     */
    public function range()
    {
        return $this->belongsTo(Range::class);
    }
}
