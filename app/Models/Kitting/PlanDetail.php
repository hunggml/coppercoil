<?php

namespace App\Models\Kitting;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class PlanDetail extends Model
{
	const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Production_Detail';
	protected $primaryKey = 'ID';
	protected function serializeDate(DateTimeInterface $date)
      {
          return $date->format('Y-m-d H:i:s');
      }
      protected $fillable   = [
	  	'Plan_ID'
	  	,'Product_ID'
	  	,'Face'
      	,'Machine_ID'
      	,'Quantity_1'
     	      ,'Quantity_2'
      	,'Quantity_3'
      	,'Quantity_4'
      	,'Quantity_5'
      	,'Quantity_6'
      	,'Quantity_7'
      	,'Quantity_8'
      	,'Quantity_9'
      	,'Quantity_10'
      	,'Quantity_11'
      	,'Quantity_12'
      	,'Quantity_13'
      	,'Quantity_14'
      	,'Quantity_15'
      	,'Quantity_16'
      	,'Quantity_17'
      	,'Quantity_18'
      	,'Quantity_19'
      	,'Quantity_20'
      	,'Quantity_21'
      	,'Quantity_22'
      	,'Quantity_23'
      	,'Quantity_24'
      	,'Quantity_25'
      	,'Quantity_26'
      	,'Quantity_27'
      	,'Quantity_28'
      	,'Quantity_29'
      	,'Quantity_30'
      	,'Quantity_31'
      	,'Production_Shift'
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

	public function product()
	{
	    return $this->hasOne('App\Models\MasterData\MasterProduct', 'ID', 'Product_ID')->whereIsdelete(0);
	}

	public function machine()
	{
	    return $this->hasOne('App\Models\MasterData\MasterMachine', 'ID', 'Machine_ID')->whereIsdelete(0);
	}
    
      
}
