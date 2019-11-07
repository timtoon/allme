<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stylist extends Model
{
	public function slots()
	{
		return $this->hasMany('App\Slot');
	}

}
