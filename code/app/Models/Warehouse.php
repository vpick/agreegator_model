<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    public function state(){
        return $this->belongsTo(State::class);
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
}
