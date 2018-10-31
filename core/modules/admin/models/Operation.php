<?php

namespace app\modules\admin\models;

use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "operation".
 *
 * @property int $id
 * @property int $type Тип операции
 * @property string $sum Общая сумма
 * @property $products[] Товары
 * @property int $status Публиковать
 * @property string $updated_at Дата обновления
 * @property string $created_at Дата создания
 *
 */
class Operation extends ActiveRecord {
	const OPERATION_BUY = 0;
	const OPERATION_SELL = 1;

	public static function tableName(){
		return 'operation';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(){
		return [
			[['type', 'status'], 'integer'],
			[['sum'], 'number'],
			[['updated_at', 'created_at'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(){
		return [
			'id'         => 'ID',
			'type'       => 'Тип операции',
			'sum'        => 'Общая сумма',
			'status'     => 'Публиковать',
			'updated_at' => 'Дата обновления',
			'created_at' => 'Дата создания',
		];
	}

	public function afterFind(){
		if ($this->products){
			$this->products = Json::decode($this->products);
		}
		parent::afterFind();
	}

	public function beforeSave($insert){
		if ($this->products){
			$this->products = Json::encode($this->products);
		}

		return parent::beforeSave($insert);
	}

	public function getList(){
		return [
			static::OPERATION_BUY  => 'Покупка',
			static::OPERATION_SELL => 'Продажа',
		];
	}

	public function getTypeName(){
		return static::getList()[$this->type];
	}
}
