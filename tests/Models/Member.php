<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/28
 * Time: 18:41
 */

namespace W7\Laravel\CacheModel\Tests\Models;


use W7\Laravel\CacheModel\Model;

/**
 * Class Member
 * @package W7\Laravel\CacheModel\Tests\Models
 *
 * @property int    $uid
 * @property string $invite_code
 * @property string $salt
 * @property string $username
 * @property string $password
 */
class Member extends Model
{
	public $timestamps = false;
	
	protected $connection = 'prox';
	
	protected $table = 'mc_members';
	
	protected $primaryKey = 'uid';
	
	// protected $with = ['memberCount'];
	
	protected $useCache = true;
	
	protected $fillable = [
		'uid',
		'username',
		'password',
		//		'salt',
		//		'encrypt',
	];
	
	protected $visible = [
		'uid', 'mobile', 'password', 'salt',
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