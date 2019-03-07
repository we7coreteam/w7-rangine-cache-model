<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/29
 * Time: 15:42
 */

namespace W7\Laravel\CacheModel\Tests;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use W7\Laravel\CacheModel\Caches\Tag;
use W7\Laravel\CacheModel\Tests\Models\Member;
use W7\Laravel\CacheModel\Tests\Models\MemberCount;

class TestModel extends TestCase
{
	/**
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 */
	public function setUp()
	{
		parent::setUp(); // TODO: Change the autogenerated stub
		
		\W7\Laravel\CacheModel\Caches\Cache::setCacheResolver(Cache::store());
	}
	
	public function testAAA()
	{
		$cache = new \W7\Laravel\CacheModel\Caches\Cache();
		$cache->set('a', 123);
		jd($cache->get('a'));
	}
	
	public function testImpl()
	{
		$obj = new \stdClass();
		$obj->name = 'js';
		
		$cache = \W7\Laravel\CacheModel\Caches\Cache::getCacheResolver();
		$cache->set('a', $obj, 10);
		
		jd($cache->get('a'));
	}
	
	public function testCache() {
		// $key = '384942644ebcd01d369fc1194b4d7362:1';
		
		$obj = new \stdClass();
		$obj->name = 'js';;
		
		$key = 'aa';
		
		$value = $obj;
		
		$cache = \W7\Laravel\CacheModel\Caches\Cache::singleton();
		$cache->set($key, $value);
		
		$model =  $cache->get($key);
		jd($model);
	}
	
	public function testTag()
	{
		$tag = new Tag();
		$key = Tag::getCacheKey(1, 'default:members');
		jd($key);
	}
	
	public function testA()
	{
		jd(join(':', []));
	}
	
	public function testFind()
	{
		// Member::flush();
		
		$uid  = 1;
		$user = Member::query()->find($uid);
		$user = Member::query()->find($uid);
		jd($user);
	}
	
	
	

	
	public function testFinds()
	{
		//		Member::flush();
		
		$uid = [1, 2, 5];
		Member::query()->find($uid);
		$users = Member::query()->find($uid);
		
		jd($users);
	}
	
	public function testMObile()
	{
		$uid = [1, 2, 5];
		MemberCount::query()->find($uid);
		$membercount = MemberCount::query()->find($uid);
		
		jd($membercount);
	}
	
	public function testFindsColumns()
	{
		$uid     = [1, 2, 5];
		$columns = ['uid', 'username'];
		Member::query()->find($uid, $columns);
		$users = Member::query()->find($uid, $columns);
		jd($users);
	}
	
	public function testInsert()
	{
		$data = [
			'uid'      => 111,
			'username' => 'abc123',
			'password' => 'pwd111',
			'salt'     => '123',
			'encrypt'  => '123',
		];
		Member::query()->insert($data);
		
		Member::query()->find(111);
		Member::query()->find(111);
		jd();
	}
	
	public function testFlush()
	{
		ll('find');
		Member::query()->find(1);
		ll('flush');
		Member::flush();
		ll('find');
		Member::query()->find(1);
		ll('find');
		Member::query()->find(1);
	}
	
	/**
	 */
	public function testSelect()
	{
		Member::flush();
		echo 'select query run once', PHP_EOL;
		Member::query()->find(1);
		Member::query()->find(1);
		echo PHP_EOL;
		
		Member::flush();
		echo 'select query run twice', PHP_EOL;
		Member::query()->find([1, 5], ['uid', 'username']);
		Member::query()->find([1, 5], ['uid', 'username']);
		echo PHP_EOL;
		
		Member::flush();
		echo 'select query run once', PHP_EOL;
		Member::query()->find([1, 5]);
		Member::query()->find([1, 5]);
		echo PHP_EOL;
		
		Member::flush();
		echo 'select query run twice', PHP_EOL;
		Member::query()->find([1, 2, 3, 4, 5], ['uid'])->keyBy('uid');
		Member::query()->find([1, 2, 3, 4, 5], ['uid'])->keyBy('uid');
		echo PHP_EOL;
		
		Member::flush();
		echo 'select query run once', PHP_EOL;
		Member::query()->find([1, 2, 3, 4, 5])->keyBy('uid');
		Member::query()->find([1, 2, 3, 4, 5])->keyBy('uid');
		echo PHP_EOL;
		
		Member::flush();
		echo 'select query run 3 times', PHP_EOL;
		Member::query()->find([1, 2, 3, 4, 5], ['uid'])->keyBy('uid');
		Member::query()->find([1, 2, 3, 4, 5], ['uid'])->keyBy('uid');
		Member::query()->find([1, 2, 3, 4, 5])->keyBy('uid');
		Member::query()->find([1, 2, 3, 4, 5])->keyBy('uid');
		$this->assertTrue(true);
	}
	
	/**
	 */
	public function testSelectModelExist()
	{
		$uid = 123456;
		
		$user = Member::query()->find($uid);
		$this->assertTrue(!empty($user));
		
		echo "member find {$uid} again", PHP_EOL;
		$user = Member::query()->find($uid);
		$this->assertTrue(!empty($user));
	}
	
	/**
	 */
	public function testUpdate()
	{
		$uid = 1;
		
		/**
		 * @var $user Member
		 */
		$user = Member::query()->find($uid);
		$this->assertTrue(!empty($user));
		
		$user->invite_code = rand(1, 100000);
		$user->save();
		
		Member::query()->find($uid);
		Member::query()->find($uid);
	}
	
	public function testCreate()
	{
		$uid = 250050;
		DB::table('members')->where('uid', $uid)->delete();
		
		Member::query()->find($uid);
		
		Member::query()->forceCreate([
			'uid'      => 250050,
			'username' => 'cache_model',
			'password' => str_random(8),
			'salt'     => str_random(6),
			'encrypt'  => str_random(8),
		]);
	}
	
	public function testInsertGetId()
	{
		$uid = 250050;
		
		$user = Member::query()->find($uid);
		if (!empty($user)) {
			$user->delete();
		}
		
		$value = [
			'uid'      => $uid,
			'username' => 'cache_model',
			'password' => str_random(8),
			'salt'     => str_random(6),
			'encrypt'  => str_random(8),
		];
		$id    = Member::query()->insertGetId($value);
		jd('iiid', $id);
	}
	
	private function createUser()
	{
		$user = Member::query()->forceCreate([
			'uid'      => 250050,
			'username' => 'cache_model',
			'password' => str_random(8),
			'salt'     => str_random(6),
			'encrypt'  => str_random(8),
		]);
		
		return $user;
	}
	
	
	//	public function testInsert()
	//	{
	//		$user = Member::query()->newModelInstance([
	//			'uid'      => 250050,
	//			'username' => 'cache_model',
	//			'password' => str_random(8),
	//			'salt'     => str_random(6),
	//			'encrypt'  => str_random(8),
	//		]);
	//		$user->save();
	//	}
	
	public function testLL()
	{
		ll('a', 'b');
	}
	
	
	public function testWith()
	{
		// Member::cacheFlushAll();
		$uid  = 1;
		$user = Member::query()->with(['apps'])->find($uid);
	}
	
	public function testFlushAll()
	{
		Member::query()->find(1);
		MemberCount::query()->find(1);
		
		MemberCount::flush();
		
		echo 'select query twice', PHP_EOL;
		
		Member::query()->find(1);
		MemberCount::query()->find(1);
		
		Member::query()->find(1);
		MemberCount::query()->find(1);
	}
}