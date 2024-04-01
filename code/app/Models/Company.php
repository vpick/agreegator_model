<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Company extends Model
{
    use HasFactory;
    public function state(){
        return $this->belongsTo(State::class);
    }
    public function client(){
        return $this->hasMany(Client::class)->with('warehouse');
    }
}
