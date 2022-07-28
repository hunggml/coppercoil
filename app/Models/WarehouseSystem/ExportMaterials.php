<?php

namespace App\Models\WarehouseSystem;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ExportMaterials extends Model
{
	const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Export_Materials';
	protected $primaryKey = 'ID';
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
	protected $fillable   = [
  	 'Materials_ID'
    ,'Go'
  	,'Quantity'
    ,'Count'
    ,'Type'
    ,'Status'
    ,'To'
    ,'Machine_ID'
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
  public function machine()
  {
    return $this->hasOne('App\Models\MasterData\MasterMachine', 'ID', 'Machine_ID')->whereIsdelete(0);
  }
  public function location()
  {
    return $this->hasOne('App\Models\MasterData\MasterWarehouseDetail', 'ID', 'Warehouse_Detail_ID')->whereIsdelete(0);
  }
  public function go()
  {
    return $this->hasOne('App\Models\MasterData\MasterWarehouseDetail', 'Area', 'Go')->whereIsdelete(0);
  }
  public function to()
  {
    return $this->hasOne('App\Models\MasterData\MasterWarehouseDetail', 'Area', 'To')->whereIsdelete(0);
  }
  public function go_area()
  {
    return $this->hasOne('App\Models\MasterData\MasterArea', 'ID', 'Go')->whereIsdelete(0);
  }
  public function to_area()
  {
    return $this->hasOne('App\Models\MasterData\MasterArea', 'ID', 'To')->whereIsdelete(0);
  }
  public function detail()
  {
    return $this->hasMany('App\Models\WarehouseSystem\ExportDetail', 'Export_ID', 'ID')->whereIsdelete(0);
  }
}
