<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MasterErorr extends Model
{
    protected $connection = 'sqlsrv2';
	// const CREATED_AT      = 'Created_at';
	// const UPDATED_AT      = 'Updated_at';
    public $timestamps = false;
	protected $table      = 'Error';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
        'ID'
        ,'Error_Description'
  ];

}
