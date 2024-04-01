<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;
    public function courier(){
        return $this->belongsTo(AppLogistics::class,'dsp','id');
    }
}
