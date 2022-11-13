<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Range extends Model
{
    use HasFactory;

    /**
     * Get the events associated with the range.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the prices associated with the range.
     */
    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
