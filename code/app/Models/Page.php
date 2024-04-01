<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Page extends Model
{
    use HasFactory;
    public function userpermission(){
        return $this->hasMany(UserPermission::class);
    }
}
