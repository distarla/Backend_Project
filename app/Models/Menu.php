<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable=['name', 'starters', 'main', 'desserts', 'buffet', 'bar'];

    public function regras($id=-1) {
        return [
            "name"=>"required|unique:menus,name,$id",
        ];
    }

    public function feedback() {
        return [
            "required"=>"O campo :attribute é obrigatório",
            "name.unique"=>"O Menu indicado já existe"
        ];
    }

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
