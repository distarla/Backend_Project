<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable=['title', 'date', 'range_id', 'menu_id'];

    public function regras($id=-1) {
        return [
            "title"=>"required",
            "date"=>"required|date_format:Y-m-d",
            "menu_id"=>"required",
            "range_id"=>"required"
        ];
    }

    public function feedback() {
        return [
            "required"=>"O campo :attribute é obrigatório",
            "date"=>"A data indicada não é válida"
        ];
    }

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
     * The clients that belong to the event.
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class,'schedules', 'event_id', 'client_id')->withTimestamps();
    }
}
