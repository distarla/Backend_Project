<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable=['menu_id','range_id', 'value'];

    public function regras($id=-1) {
        return [
            "menu_id"=>"required",
            "range_id"=>"required",
            "value"=>"required"
        ];
    }

    public function feedback() {
        return [
            "required"=>"O campo :attribute é obrigatório"
        ];
    }

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
