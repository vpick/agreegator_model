<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticsMapping extends Model
{
    use HasFactory;
    public function courier(){
        return $this->belongsTo(AppLogistics::class,'partner_id','id');
    }
}
