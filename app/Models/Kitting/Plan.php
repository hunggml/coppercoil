<?php

namespace App\Models\Kitting;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Plan extends Model
{
    const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Production_Plan';
	protected $primaryKey = 'ID';
	protected function serializeDate(DateTimeInterface $date)
	{
	    return $date->format('Y-m-d H:i:s');
	}
	protected $fillable   = [
	  	'Name'
	  	,'Symbols'
	  	,'Note'
	  	,'Time_Created'
	  	,'User_Created'
	  	,'Time_Updated'
	  	,'User_Updated'
	  	,'IsDelete'
	];

	public function user_created()
	{
	    return $this->hasOne('App\Models\User', 'id', 'User_Created')->whereIsdelete(0);
	}

	public function user_updated()
	{
	    return $this->hasOne('App\Models\User', 'id', 'User_Updated')->whereIsdelete(0);
	}

	public function detail()
	{
	    return $this->hasMany('App\Models\Kitting\PlanDetail', 'Plan_ID', 'ID')->whereIsdelete(0);
	}
}
