<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shoppingcart extends Model
{
	protected $table = 'carts';

	protected $fillable = [
        'user_id',
    ];
}
