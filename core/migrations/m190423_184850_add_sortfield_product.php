<?php

use app\modules\admin\models\Product;
use yii\db\Migration;

/**
 * Class m190423_184850_add_sortfield_product
 */
class m190423_184850_add_sortfield_product extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp(){
        $this->addColumn(Product::tableName(), 'report_sort', $this->integer(4)->after('status')->defaultValue(1));
        $this->addColumn(Product::tableName(), 'operation_sort', $this->integer(4)->after('status')->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(){
        $this->dropColumn(Product::tableName(), 'operation_sort');
        $this->dropColumn(Product::tableName(), 'report_sort');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190423_184850_add_sortfield_product cannot be reverted.\n";

        return false;
    }
    */
}
