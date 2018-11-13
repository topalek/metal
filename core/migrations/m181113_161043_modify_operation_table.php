<?php

use app\modules\admin\models\Operation;
use yii\db\Migration;

/**
 * Class m181113_161043_modify_operation_table
 */
class m181113_161043_modify_operation_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp(){
		$this->alterColumn(Operation::tableName(), 'created_at', $this->date()->comment('Дата создания'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown(){
		$this->alterColumn(Operation::tableName(), 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата создания'));

	}

}
