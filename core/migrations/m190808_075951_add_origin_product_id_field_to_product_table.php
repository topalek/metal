<?php

use app\modules\admin\models\Product;
use yii\db\Migration;

/**
 * Class m190808_075951_add_origin_product_id_field_to_product_table
 */
class m190808_075951_add_origin_product_id_field_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Product::tableName(), 'origin_id', $this->integer()->null()->defaultValue(null)->comment("Основной товар")->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Product::tableName(), "origin_id");
    }
}
