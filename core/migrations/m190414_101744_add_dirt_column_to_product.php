<?php

use app\modules\admin\models\Product;
use yii\db\Migration;

/**
 * Class m190414_101744_add_dirt_column_to_product
 */
class m190414_101744_add_dirt_column_to_product extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp(){
        $this->addColumn(Product::tableName(), 'dirt', $this->integer(3)->defaultValue(0)->after('sale_price')->comment('Засор'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(){
        $this->dropColumn(Product::tableName(), 'dirt');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190414_101744_add_dirt_column_to_product cannot be reverted.\n";

        return false;
    }
    */
}
