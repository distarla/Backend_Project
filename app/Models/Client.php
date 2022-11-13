<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable=['name','address', 'zip', 'city', 'country', 'phone', 'email', 'idCard', 'expiry', 'nif'];

    public function regras($id=-1) {
        return [
            "name"=>"required",
            "email"=>"email:strict",
            "expiry"=>"date_format:Y-m-d"
        ];
    }

    public function feedback() {
        return [
            "required"=>"O campo :attribute é obrigatório",
            "email"=>"O Email indicado não é válido",
            "expiry"=>"A data de validade não é válida"
        ];
    }

    /**
     * The events that belong to the client.
     */
    public function events()
    {
        return $this->belongsToMany(Event::class,'schedules', 'client_id', 'event_id')->withTimestamps();
    }
}
