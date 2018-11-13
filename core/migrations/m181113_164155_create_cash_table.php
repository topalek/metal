<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cash`.
 */
class m181113_164155_create_cash_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp(){
		$this->createTable('cash', [
			'id'         => $this->primaryKey(),
			'title'      => $this->string(255)->notNull()->comment('Название'),
			'sum'        => $this->money(10, 2)->comment('Сумма'),
			'created_at' => $this->date()->comment('Дата проведения'),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown(){
		$this->dropTable('cash');
	}
}
