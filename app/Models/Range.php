<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Range extends Model
{
    use HasFactory;

    protected $fillable=['initial_value','final_value'];

    public function regras($id=-1) {
        return [
            "initial_value"=>"required|unique:ranges,initial_value,$id|lt:final_value|integer",
            "final_value"=>"required|unique:ranges,final_value,$id|gt:initial_value|integer"
        ];
    }

    public function feedback() {
        return [
            "required"=>"O campo :attribute é obrigatório",
            "initial_value.unique"=>"O valor indicado já está atribuído",
            "final_value.unique"=>"O valor indicado já está atribuído",
            "lt"=>"O valor tem que ser inferior ao valor final",
            "gt"=>"O valor tem que ser superior ao valor inicial",
            "integer"=>"O campo :attribute tem de ser um número inteiro"
        ];
    }

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
