<?php

namespace app\modules\admin\models;

/**
 * This is the model class for table "operation".
 *
 * @property int $id
 * @property int $type Тип операции
 * @property string $sum Общая сумма
 * @property int $status Публиковать
 * @property string $updated_at Дата обновления
 * @property string $created_at Дата создания
 *
 * @property OperationProduct[] $operationProducts
 * @property Product[] $products
 */
class Operation extends \yii\db\ActiveRecord {
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

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOperationProducts(){
		return $this->hasMany(OperationProduct::class, ['operation_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProducts(){
		return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable('operation_product', ['operation_id' => 'id']);
	}
}
