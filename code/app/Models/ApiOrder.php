<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiOrder extends Model
{
    use HasFactory;
	protected $fillable = [
    'order_no',
	'invoice_no',
	'invoice_amount',
	'omnee_order',
	'order_request',
	'request_partner',
	'business_account',
   ];
}
