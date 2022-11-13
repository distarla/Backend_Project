<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * Get the range associated with the event.
     */
    public function range()
    {
        return $this->belongsTo(Range::class);
    }

    /**
     * Get the menu associated with the event.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the price associated with the event.
     */
    public function price()
    {
        return $this->belongsTo(Price::class);
    }

    /**
     * The clients that belong to the event.
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class,'schedules', 'event_id', 'client_id');
    }
}
