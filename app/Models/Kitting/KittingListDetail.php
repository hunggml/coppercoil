<?php

namespace App\Models\Kitting;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class KittingListDetail extends Model
{
    const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Kitting_List_Detail';
	protected $primaryKey = 'ID';
	protected function serializeDate(DateTimeInterface $date)
	{
	    return $date->format('Y-m-d H:i:s');
	}
	protected $fillable   = [
	  	'Kitting_List_ID'
      	,'Product_ID'
      	,'BOM_ID'
      	,'Materials_ID'
      	,'Machine_ID'
      	,'Quantity'
      	,'Label_ID'
		,'Warehouse_Detail_ID'
      	,'Face'
      	,'Production_Shift'
      	,'Status'
      	,'Type'
      	,'Note'
      	,'User_Created'
      	,'Time_Created'
      	,'User_Updated'
      	,'Time_Updated'
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

	public function bom()
	{
	    return $this->hasOne('App\Models\MasterData\MasterBOM', 'ID', 'BOM_ID')->whereIsdelete(0);
	}

	public function materials()
	{
	    return $this->hasOne('App\Models\MasterData\MasterMaterials', 'ID', 'Materials_ID')->whereIsdelete(0);
	}

	public function machine()
	{
	    return $this->hasOne('App\Models\MasterData\MasterMachine', 'ID', 'Machine_ID')->whereIsdelete(0);
	}

	public function label()
	{
	    return $this->hasOne('App\Models\PrintLabel\Label', 'ID', 'Label_ID')->whereIsdelete(0);
	}

	public function warehouse()
	{
	    return $this->hasOne('App\Models\MasterData\MasterWarehouseDetail', 'ID', 'Warehouse_Detail_ID')->whereIsdelete(0);
	}

	public function kitting()
	{
	    return $this->hasOne('App\Models\Kitting\KittingList', 'ID', 'Kitting_List_ID')->whereIsdelete(0);
	}
}
