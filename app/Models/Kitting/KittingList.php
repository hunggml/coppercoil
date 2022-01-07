<?php

namespace App\Models\Kitting;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class KittingList extends Model
{
    const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Kitting_List';
	protected $primaryKey = 'ID';
	protected function serializeDate(DateTimeInterface $date)
	{
	    return $date->format('Y-m-d H:i:s');
	}
	protected $fillable   = [
	  	'Kitting_Name'
      	,'Plan_Detail_ID'
      	,'Kitting_Day'
      	,'Status'
      	,'Note'
      	,'User_Created'
      	,'Time_Created'
      	,'User_Updated'
      	,'Time_Updated'
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
	    return $this->hasOne('App\Models\Kitting\PlanDetail', 'ID', 'Plan_Detail_ID')->whereIsdelete(0);
	}

	public function detail_kitting()
	{
	    return $this->hasMany('App\Models\Kitting\KittingListDetail', 'Kitting_List_ID', 'ID')->whereIsdelete(0);
	}
}
