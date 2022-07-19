<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MasterBOM extends Model
{
  const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Master_BOM';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
  	'Product_BOM_ID'
  	,'Materials_BOM_ID'
  	,'Product_ID'
  	,'Quantity_Product'
  	,'Materials_ID'
  	,'Quantity_Materials'
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
  public function materials()
  {
    return $this->hasOne('App\Models\MasterData\MasterMaterials', 'ID', 'Materials_ID')->whereIsdelete(0);
  }
}
