<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MasterArea extends Model
{
	const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Master_Area';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
  	'Name'
  	,'Time_Created'
  	,'Time_Updated'
  	,'IsDelete'
  ];





}
