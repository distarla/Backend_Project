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
            "menu_id"=>"required|integer",
            "range_id"=>"required|integer",
            "value"=>"required|integer"
        ];
    }

    public function feedback() {
        return [
            "required"=>"O campo :attribute é obrigatório",
            "integer"=>"O campo :attribute tem de ser um número inteiro"
        ];
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
