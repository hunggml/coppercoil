<?php

namespace App\Models\WarehouseSystem;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class TransferMaterials extends Model
{
	const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Transfer_Materials';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
    'Export_ID'
  	,'Materials_ID'
    ,'Export_Detail_ID'
  	,'Pallet_ID'
    ,'Box_ID'
    ,'Warehouse_Detail_ID_Go'
    ,'Warehouse_Detail_ID_To'
    ,'Status'
    ,'Quantity'
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

  public function materials()
  {
    return $this->hasOne('App\Models\MasterData\MasterMaterials', 'ID', 'Materials_ID')->whereIsdelete(0);
  }
  public function location_go()
  {
    return $this->hasOne('App\Models\MasterData\MasterWarehouseDetail', 'ID', 'Warehouse_Detail_ID_Go')->whereIsdelete(0);
  }
  public function location_to()
  {
    return $this->hasOne('App\Models\MasterData\MasterWarehouseDetail', 'ID', 'Warehouse_Detail_ID_To')->whereIsdelete(0);
  }
}
