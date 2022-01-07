<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MasterWarehouseDetail extends Model
{
  const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Master_Warehouse_Detail';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
  	'Name'
  	,'Symbols'
  	,'Warehouse_ID'
  	,'MAC'
  	,'Position_Led'
    ,'R'
    ,'G'
    ,'B'
    ,'Status_Led'
  	,'Quantity_Unit'
    ,'Min_Unit'
  	,'Unit_ID'
  	,'Quantity_Packing'
  	,'Packing_ID'
   	,'Group_Materials_ID'
    ,'Floor'
    ,'Accept'
    ,'Email'
    ,'Email2'
  	,'Note'
  	,'Time_Created'
  	,'User_Created'
  	,'Time_Updated'
  	,'User_Updated'
  	,'IsDelete'
  ];

  public function inventory()
  {
    return $this->hasMany('App\Models\WarehouseSystem\ImportDetail', 'Warehouse_Detail_ID', 'ID')->where('Inventory','!=', 0)->whereIsdelete(0);
  }

  public function inventory2()
  {
    return $this->hasMany('App\Models\WarehouseSystem\ImportDetail', 'Warehouse_Detail_ID', 'ID')->whereIsdelete(0);
  }
  public function inventory1()
  {
    return $this->hasMany('App\Models\WarehouseSystem\ImportDetail', 'Warehouse_Detail_ID', 'ID')->where('Inventory', 0)->whereIsdelete(0);
  }
  public function inventory_null()
  {
    return $this->hasMany('App\Models\WarehouseSystem\ImportDetail', 'Warehouse_Detail_ID', 'ID')->where('Inventory', 0)->whereIsdelete(0);
  }
  public function group_materials()
  {
    return $this->hasOne('App\Models\MasterData\MasterGroupMaterials', 'ID', 'Group_Materials_ID')->whereIsdelete(0);
  }
}
