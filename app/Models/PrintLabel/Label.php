<?php

namespace App\Models\PrintLabel;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Label extends Model
{
	const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Label_New';
	
	protected $primaryKey = 'ID';
	protected function serializeDate(DateTimeInterface $date)
	{
	    return $date->format('Y-m-d H:i:s');
	}
	//Thêm Cột Drawing
	//Cột Lot_Number Thay thế cho Số Thùng
	protected $fillable   = [
    	'Part_ID'
    	,'Materials_ID'
	    ,'Lot_Number'
	    ,'Quantity'
	    ,'Inventory'
		,'Drawing'
	    ,'Parent'
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

    public function materials()
	{
		return $this->hasOne('App\Models\MasterData\MasterMaterials', 'ID', 'Materials_ID')->whereIsdelete(0);
	}

	public function children()
	{
		return $this->hasMany('App\Models\Printlabel\Label', 'Parent', 'ID')->whereIsdelete(0);
	}

	public function import()
	{
		return $this->hasMany('App\Models\WarehousesManagement\ImportMaterials', 'Label_ID', 'ID')->whereIsdelete(0);
	}

	public function export()
	{
		return $this->hasMany('App\Models\QualityWarehouses\ExportMaterials', 'Label_ID', 'ID')->whereIsdelete(0);
	}

	public function import_filter()
	{
		return $this->hasMany('App\Models\WarehousesManagement\ImportMaterials', 'Label_ID', 'ID')->whereIsdelete(0)->whereLock(0);
	}

}
