<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MasterMaterials extends Model
{
  const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Master_Materials';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
  	'Name'
  	,'Symbols'
  	,'Unit_ID'
  	,'Packing_ID'
  	,'Supplier_ID'
  	,'Model'
  	,'Standard_Unit'
  	,'Standard_Packing'
    ,'Part_ID'
    ,'Difference'
    ,'Wire_Type'
    ,'Spec'
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

  public function unit()
  {
    return $this->hasOne('App\Models\MasterData\MasterUnit', 'ID', 'Unit_ID')->whereIsdelete(0);
  }

  public function packing()
  {
    return $this->hasOne('App\Models\MasterData\MasterPacking', 'ID', 'Packing_ID')->whereIsdelete(0);
  }

  public function supplier()
  {
    return $this->hasOne('App\Models\MasterData\MasterSupplier', 'ID', 'Supplier_ID')->whereIsdelete(0);
  }

  public function group()
  {
    return $this->hasMany('App\Models\MasterData\GroupMaterials', 'Materials_ID', 'ID')->whereIsdelete(0);
  }

  public function label()
  {
    return $this->hasMany('App\Models\PrintLabel\Label', 'Materials_ID', 'ID')->whereIsdelete(0);
  }

  public function inventory()
  {
    return $this->hasMany('App\Models\WarehousesManagement\ImportMaterials', 'Materials_ID', 'ID')->whereIsdelete(0);
  }

}
