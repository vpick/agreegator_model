<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
   public function state(){
        return $this->belongsTo(State::class);
    }
    
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function warehouse(){
        return $this->hasMany(Warehouse::class);
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
