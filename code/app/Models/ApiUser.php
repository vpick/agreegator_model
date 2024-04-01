<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens; // Make sure this line is included

class ApiUser extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'api_users';
    protected $guard = 'client';
}
?>
