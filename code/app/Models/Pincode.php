<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    use HasFactory;
	protected $fillable = ['Pincode', 'District', 'City','State']; // Replace other_column1 and other_column2 with your actual columns

}
