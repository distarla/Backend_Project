<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable=['event_id', 'client_id'];

    public function regras($id=-1) {
        return [
            "event_id"=>"required|integer",
            "client_id"=>"required|integer"
        ];
    }

    public function feedback() {
        return [
            "required"=>"O campo :attribute é obrigatório",
            "integer"=>"O campo :attribute tem de ser um número inteiro"
        ];
    }
}
