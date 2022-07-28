<?php

namespace App\Models\WarehouseSystem;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class StockNG extends Model
{
	const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Stock_NG';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
    'Warehouse_Detail_ID'
  	,'Product_ID'
    ,'Materials_ID'
    ,'Box_ID'
    ,'Supplier_ID'
    ,'Quantity'
    ,'Reuse'
    ,'Cancel'
    ,'Format'
    ,'Type'
    ,'Go'
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
  public function product()
  {
    return $this->hasOne('App\Models\MasterData\MasterProduct', 'ID', 'Product_ID')->whereIsdelete(0);
  }
}
