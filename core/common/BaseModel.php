<?php
/**
 * Created by PhpStorm.
 * User: yurik
 * Date: 23.05.14
 * Time: 10:36
 */

namespace app\common;

use ReflectionClass;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\caching\ChainedDependency;
use yii\caching\DbDependency;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * @property mixed status
 */
class BaseModel extends ActiveRecord {
	const STATUS_PUBLISHED = 1;
	const STATUS_NOT_PUBLISHED = 0;
	const DEFAULT_CACHE_DURATION = 86400;

	/**
	 * @param array $tables
	 * @param string $field
	 *
	 * @return ChainedDependency
	 */
	static function getChainedDependency($tables = [], $field = 'updated_at'){
		$dependency   = new ChainedDependency();
		$dependencies = [];
		if ( ! is_array($tables)){
			$tables = [$tables];
		}
		foreach ($tables as $table){
			$dependencies[] = new DbDependency([
				'sql' => "SELECT MAX($field) FROM $table"
			]);
		}
		$dependency->dependencies = $dependencies;

		return $dependency;
	}

	public static function getList($map = true, array $attributes = [], $where = null, $orderBy = null){
		$defaultAttributes = ['id', 'title'];
		if (empty($attributes)){
			$attributes = $defaultAttributes;
		}
		$models = static::getDb()->cache(function () use ($where, $orderBy){
			$query = static::find();

			if ($where){
				$query->where($where);
			}

			if ($orderBy){
				$query->orderBy($orderBy);
			}

			return $query->all();
		}, static::DEFAULT_CACHE_DURATION, static::getDbDependency());

		if ($map){
			return ArrayHelper::map($models, $attributes[0], $attributes[1]);
		}

		return $models;
	}

	/**
	 * @param null $table
	 * @param string $field
	 *
	 * @return DbDependency
	 */
	static function getDbDependency($table = null, $field = 'updated_at'){
		if ($table == null){
			$table = self::tableName();
		}

		$dependency      = new DbDependency();
		$dependency->sql = 'SELECT MAX(' . $field . ') FROM ' . $table;

		return $dependency;
	}

	public function isPublished(){
		return $this->status = self::STATUS_PUBLISHED;
	}

	public function getStatusName(){

        return ArrayHelper::getValue(self::getStatusList(), $this->status);
	}

	public static function getStatusList(){
		return [
			self::STATUS_PUBLISHED     => 'Опубликован',
			self::STATUS_NOT_PUBLISHED => 'Не опубликован'
		];
	}

	/**
	 * @return array
	 */
	public function behaviors(){
		return [
			'timestamp' => [
				'class'      => TimestampBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
					ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
				],
				'value'      => new Expression('NOW()'),
			],
		];
	}

	/**
	 * Return model uploads dir.
	 * example - /uploads/news/1/
	 *
	 * @param bool $absoluteUrl
	 *
	 * @return string
	 * @throws \ReflectionException
	 * @internal param bool $scheme
	 */
	public function modelUploadsUrl($absoluteUrl = false){
		if ($absoluteUrl){
			return Yii::$app->urlManager->createAbsoluteUrl([$this->moduleUploadsUrl() . $this->id]) . '/';
		}

		return Url::to($this->moduleUploadsUrl() . $this->id . '/');
	}

	/**
	 * Return Module uploads dir.
	 * example - /uploads/news/
	 * @return string
	 * @throws \ReflectionException
	 */
	public static function moduleUploadsUrl(){
		$path = DIRECTORY_SEPARATOR . uploadsDirName() . DIRECTORY_SEPARATOR
		        . strtolower(Inflector::underscore(static::getModelName())) . '/';

		return str_replace('//', '/', $path);
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public static function getModelName(){
		$reflect = new ReflectionClass(static::class);

		return $reflect->getShortName();
	}

	public function beforeDelete(){
		FileHelper::removeDirectory($this->modelUploadsPath());

		return parent::beforeDelete();
	}

	/**
	 * Return model uploads path.
	 * example - /var/www/sitename/core/../uploads/news/1/
	 *
	 * @param bool $createIfNotExists
	 *
	 * @return string
	 * @throws \ReflectionException
	 * @throws \yii\base\Exception
	 */
	public function modelUploadsPath($createIfNotExists = false){
		$path = $this->moduleUploadsPath() . $this->id . '/';
		$path = str_replace('//', '/', $path);
		if ($createIfNotExists){
			FileHelper::createDirectory($path);
		}

		return $path;
	}

	/**
	 * Return module uploads path.
	 * example - /var/www/siteName/..../uploads/news/
	 *
	 * @param bool $createIfNotExists
	 *
	 * @return string
	 * @throws \ReflectionException
	 * @throws \yii\base\Exception
	 */
	public static function moduleUploadsPath($createIfNotExists = false){
		$path = getBaseUploadsPath() . DIRECTORY_SEPARATOR
		        . strtolower(Inflector::underscore(static::getModelName())) . '/';
		$path = str_replace('//', '/', $path);
		if ($createIfNotExists){
			FileHelper::createDirectory($path);
		}

		return $path;
	}

	/**
	 * @param $table
	 *
	 * @return bool|string
	 * @throws \ReflectionException
	 */
	public function getMaxOrder($table = null){
		if ($table == null){
			$table = static::getModelName();
		}
		$maxOrder = (new Query())
			->select('MAX(ordering) as maxOrder')
			->from($table)
			->scalar();

		return $maxOrder;
	}


}