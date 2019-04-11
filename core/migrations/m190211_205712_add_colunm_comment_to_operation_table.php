<?php

use app\modules\admin\models\Operation;
use yii\db\Migration;

/**
 * Class m190211_205712_add_colunm_comment_to_operation_table
 */
class m190211_205712_add_colunm_comment_to_operation_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp(){
		$this->addColumn(Operation::tableName(), 'comment', $this->text()->comment('Коментарий для операции')->after('products'));

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown(){
		$this->dropColumn(Operation::tableName(), 'comment');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190211_205712_add_colunm_comment_to_operation_table cannot be reverted.\n";

		return false;
	}
	*/
}
