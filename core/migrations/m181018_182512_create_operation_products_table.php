<?php

use yii\db\Migration;

/**
 * Handles the creation of table `operation_products`.
 */
class m181018_182512_create_operation_products_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp(){
		$this->createTable('operation_product', [
			'operation_id' => $this->integer()->notNull(),
			'product_id'   => $this->integer()->notNull()
		]);

		$this->addPrimaryKey('pk-operation_product', 'operation_product', ['operation_id', 'product_id']);

		$this->addForeignKey('fk-operation_product-operation_id', 'operation_product', 'operation_id', 'operation', 'id', 'CASCADE', 'RESTRICT');
		$this->addForeignKey('fk-operation_product-product_id', 'operation_product', 'product_id', 'product', 'id', 'CASCADE', 'RESTRICT');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown(){
		$this->dropTable('operation_products');
	}
}
