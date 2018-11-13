<?php

namespace app\modules\admin\models;

use yii\db\ActiveRecord;
use yii\helpers\Json;
use function date;

/**
 * This is the model class for table "operation".
 *
 * @property int    $id
 * @property int    $type       Тип операции
 * @property string $sum        Общая сумма
 * @property        $products   [] Товары
 * @property int    $status     Публиковать
 * @property string $updated_at Дата обновления
 * @property string $created_at Дата создания
 *
 */
class Operation extends ActiveRecord
{
    const TYPE_BUY = 0;
    const TYPE_SELL = 1;

    public static function tableName()
    {
        return 'operation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status'], 'integer'],
            [['sum'], 'number'],
            [['updated_at', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
	        'id'         => 'ID',
	        'type'       => 'Тип операции',
	        'typeName'   => 'Тип операции',
	        'sum'        => 'Общая сумма',
	        'status'     => 'Проведенные',
	        'updated_at' => 'Дата обновления',
	        'created_at' => 'Дата создания',
        ];
    }

    public function afterFind()
    {
        if ($this->products) {
            $this->products = Json::decode($this->products);
        }
        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        if ($this->products) {
            $this->products = Json::encode($this->products);
        }
	    if ($insert){
		    $this->created_at = date('Y-m-d');
	    }
        return parent::beforeSave($insert);
    }

	public function afterSave($insert, $changedAttributes){
		parent::afterSave($insert, $changedAttributes);
		if ($insert){
			Cash::Create($this->id);
		}
	}


	public function getTypeName()
    {
        return static::getList()[$this->type];
    }

    public function getList()
    {
        return [
            static::TYPE_BUY  => 'Покупка',
            static::TYPE_SELL => 'Продажа',
        ];
    }
}
