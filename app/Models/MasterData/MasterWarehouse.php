<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MasterWarehouse extends Model
{
  const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Master_Warehouse';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
  	'Name'
  	,'Symbols'
  	,'Quantity_Rows'
  	,'Quantity_Columns'
  	,'MAC'
  	,'Quantity_Unit'
  	,'Unit_ID'
  	,'Quantity_Packing'
  	,'Packing_ID'
   	,'Group_Materials_ID'
    ,'Floor'
  	,'Note'
    ,'Accept'
    ,'Email'
    ,'Email2'
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
    return $this->hasMany('App\Models\MasterData\MasterWarehouseDetail', 'Warehouse_ID', 'ID')->whereIsdelete(0);
  }

  public function update_detail()
  {
    return $this->hasMany('App\Models\MasterData\MasterWarehouseDetail', 'Warehouse_ID', 'ID');
  }
}
