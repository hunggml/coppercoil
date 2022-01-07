<?php

namespace App\Models;

trait Basic {
	public function user_created()
	{
	    return $this->hasOne('App\Models\User', 'id', 'user_created')->whereIsdelete(0);
	}

	public function user_updated()
	{
	    return $this->hasOne('App\Models\User', 'id', 'user_updated')->whereIsdelete(0);
	}
}