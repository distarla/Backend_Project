<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    /**
     * Get the events associated with the menu.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the prices associated with the menu.
     */
    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
