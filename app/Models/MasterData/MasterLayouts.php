<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MasterLayouts extends Model
{
    protected $connection = 'sqlsrv2';
	// const CREATED_AT      = 'Created_at';
	// const UPDATED_AT      = 'Updated_at';
    public $timestamps = false;
	protected $table      = 'Layout';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
        'Title'
        ,'UserID'
        ,'FileId'
        ,'Base64Code'
        ,'JsonCode'
        ,'IsDeleted'
        ,'Width'
        ,'Height'
        ,'Status'
  ];

  public function user_created()
  {
    return $this->hasOne('App\Models\User', 'id', 'User_Created')->whereIsdelete(0);
  }

  public function user_updated()
  {
    return $this->hasOne('App\Models\User', 'id', 'User_Updated')->whereIsdelete(0);
  }




}
