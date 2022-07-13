<?php

namespace App\Models\WarehouseSystem;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ImportDetail extends Model
{
	const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Import_Detail';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
  	'Materials_ID'
    ,'Command_ID'
  	,'Box_ID'
    ,'Case_No'
    ,'Lot_No'
    ,'Pallet_ID'
    ,'Quantity'
    ,'Time_Import'
    ,'Packing_Date'
    ,'Warehouse_Detail_ID'
    ,'Inventory'
    ,'Status'
    ,'Type'
    ,'Supplier_ID'
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
  public function supplier()
  {
    return $this->hasOne('App\Models\MasterData\MasterSupplier', 'ID', 'Supplier_ID')->whereIsdelete(0);
  }
  public function location()
  {
    return $this->hasOne('App\Models\MasterData\MasterWarehouseDetail', 'ID', 'Warehouse_Detail_ID')->whereIsdelete(0);
  }

}
