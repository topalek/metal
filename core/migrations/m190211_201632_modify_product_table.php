<?php

use app\modules\admin\models\Product;
use yii\db\Migration;

/**
 * Class m190211_201632_modify_product_table
 */
class m190211_201632_modify_product_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp(){
		$this->addColumn(Product::tableName(), 'amount_for_discount', $this->integer()->comment('Кол-во для действия скидки')->after('sale_price'));
        $this->addColumn(Product::tableName(), 'discount_price', $this->money(10, 2)->comment('Цена со скидкой')->after('sale_price'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown(){
		$this->dropColumn(Product::tableName(), 'discount_price');
		$this->dropColumn(Product::tableName(), 'amount_for_discount');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190211_201632_modify_product_table cannot be reverted.\n";

		return false;
	}
	*/
}
