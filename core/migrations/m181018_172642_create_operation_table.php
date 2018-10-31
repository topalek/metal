<?php

use yii\db\Migration;

/**
 * Handles the creation of table `operation`.
 */
class m181018_172642_create_operation_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp(){
		$this->createTable('operation', [
			'id'         => $this->primaryKey(),
			'type'       => $this->boolean()->comment('Тип операции'),
			'sum'        => $this->money(10, 2)->comment('Общая сумма'),
			'products'   => $this->text()->comment('Товары'),
			'status'     => $this->boolean()->defaultValue(0)->comment('Публиковать'),
			'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment("Дата обновления"),
			'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата создания'),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown(){
		$this->dropTable('operation');
	}
}
