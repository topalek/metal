<?php

namespace app\modules\admin\models;

use yii\db\ActiveRecord;
use function date;
use function floatval;

/**
 * This is the model class for table "cash".
 *
 * @property int $id
 * @property string $title Название
 * @property string $sum Сумма
 * @property string $created_at Дата создания
 */
class Cash extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName(){
		return 'cash';
	}

	public static function Create($operation_id){
		$model     = new self();
		$operation = Operation::findOne($operation_id);
		$last      = Cash::find()->orderBy('id DESC')->one();
		if ($last){
			$model->sum        = floatval($last->sum) + floatval($operation->sum);
			$model->title      = $operation->getTypeName() . " #" . $operation->id;
			$model->created_at = date('Y-m-d');
		}else{
			$model->sum        = floatval($operation->sum);
			$model->title      = $operation->getTypeName() . " #" . $operation->id;
			$model->created_at = date('Y-m-d');
		}
		$model->save();

	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(){
		return [
			[['title'], 'required'],
			[['sum'], 'number'],
			[['created_at'], 'safe'],
			[['title'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(){
		return [
			'id'         => 'ID',
			'title'      => 'Название',
			'sum'        => 'Сумма',
			'created_at' => 'Дата проведения',
		];
	}
}
