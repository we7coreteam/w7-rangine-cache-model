<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/28
 * Time: 18:41
 */

namespace W7\Laravel\CacheModel\Tests\Models;


use W7\Laravel\CacheModel\Model;

class Member extends Model
{
	public $timestamps = false;
	
	protected $table = 'members';
	
	protected $primaryKey = 'uid';
	
	// protected $with = ['memberCount'];
	
	protected $useCache = true;
	
	protected $fillable = [
		'uid',
		'username',
		'password',
		'salt',
		'encrypt',
	];
	
	public function memberCount()
	{
		return $this->hasOne(MemberCount::class, 'uid', 'uid');
	}
	
	public function apps()
	{
		return $this->hasMany(App::class, 'uid', 'uid');
	}
}