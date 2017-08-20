<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\System\System;

/**
 * Digunakan untuk insert data ke colom create_user_id, update_user_id, 
 * create_datetime & update_datetime di setiap insert dan update menggunakan model
 * @author  Setiadi, 20 Agustus 2017
 */

class BaseModel extends Model{

	public static function boot()
	{
	    parent::boot();

	    static::creating(function($model)
	    {
	        $model->create_user_id = System::userLoginId();
	        $model->update_user_id = System::userLoginId();
	        $model->create_datetime = System::dateTime();
	        $model->update_datetime = System::dateTime();
	    });

	    static::updating(function($model)
	    {
	        $model->update_user_id = System::userLoginId();
	        $model->update_datetime = System::dateTime();
	        $version = $model->version + 1;
	        $model->version = $version;
	    });
	}

}