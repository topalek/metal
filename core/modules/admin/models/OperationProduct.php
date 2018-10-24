<?php

namespace app\modules\admin\models;

/**
 * This is the model class for table "operation_product".
 *
 * @property int $operation_id
 * @property int $product_id
 *
 * @property Operation $operation
 * @property Product $product
 */
class OperationProduct extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName(){
		return 'operation_product';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(){
		return [
			[['operation_id', 'product_id'], 'required'],
			[['operation_id', 'product_id'], 'integer'],
			[['operation_id', 'product_id'], 'unique', 'targetAttribute' => ['operation_id', 'product_id']],
			[
				['operation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Operation::className(),
				'targetAttribute'                        => ['operation_id' => 'id']
			],
			[
				['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(),
				'targetAttribute'                      => ['product_id' => 'id']
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(){
		return [
			'operation_id' => 'Operation ID',
			'product_id'   => 'Product ID',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOperation(){
		return $this->hasOne(Operation::className(), ['id' => 'operation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProduct(){
		return $this->hasOne(Product::className(), ['id' => 'product_id']);
	}
}
