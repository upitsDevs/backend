<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class device extends Model
{
    //
	protected $fillable = ['device','local_ip','global_ip','deviceID','sn'];
	protected $hidden = ['password'];
	public $timestamps = 'true';
}
