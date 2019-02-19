<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/28
 * Time: 16:33
 */

namespace W7\Laravel\CacheModel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use W7\Laravel\CacheModel\Caches\Tag;

/**
 * Class Model
 * @package W7\Laravel\CacheModel
 */
abstract class Model extends EloquentModel
{
	/**
	 * 是否使用缓存
	 * @var bool
	 */
	protected $useCache = true;
	
	/**
	 * 是否启用缓存
	 * @return bool
	 */
	public function needCache()
	{
		return $this->useCache;
	}
	
	/**
	 * 获取表的缓存命名空间
	 * @return string
	 */
	public function getCacheModelNamespace()
	{
		return ($this->getConnectionName() ?: 'default') . ':' . $this->getTable();
	}
	
	/**
	 * 清空表的缓存
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 */
	public static function flush()
	{
		Tag::flush((new static())->getCacheModelNamespace());
	}
	
	/**
	 * @param \Illuminate\Database\Query\Builder $query
	 * @return Builder|EloquentBuilder|static
	 */
	// public function newEloquentBuilder($query)
	// {
	// 	$builder = new EloquentBuilder($query);
	//
	// 	return $builder;
	// }
	
	/**
	 * @return \Illuminate\Database\Query\Builder|QueryBuilder
	 */
	protected function newBaseQueryBuilder()
	{
		$connection = $this->getConnection();
		$grammar    = $connection->getQueryGrammar();
		$processor  = $connection->getPostProcessor();
		
		$queryBuilder = new QueryBuilder($connection, $grammar, $processor);
		$queryBuilder->setCacheModel($this);
		
		return $queryBuilder;
	}
}