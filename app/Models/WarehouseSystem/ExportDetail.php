<?php

namespace App\Models\WarehouseSystem;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ExportDetail extends Model
{
	const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Export_Detail';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
  	'Materials_ID'
    ,'Export_ID'
  	,'Box_ID'
    ,'Case_No'
    ,'Pallet_ID'
    ,'Time_Export'
    ,'Warehouse_Detail_ID'
    ,'Status'
    ,'Quantity'
    ,'STT'
    ,'Type'
    ,'Transfer'
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
  public function location()
  {
    return $this->hasOne('App\Models\MasterData\MasterWarehouseDetail', 'ID', 'Warehouse_Detail_ID')->whereIsdelete(0);
  }
  public function export()
  {
    return $this->hasOne('App\Models\WarehouseSystem\ExportMaterials', 'ID', 'Export_ID')->whereIsdelete(0);
  }
}
