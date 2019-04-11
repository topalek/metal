<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m181002_193520_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product', [
            'id'         => $this->primaryKey(),
            'title'      => $this->string(255)->notNull()->comment('Название'),
            'price'      => $this->money(10, 2)->comment('Цена'),
            'sale_price' => $this->money(10, 2)->comment('Цена'),
            'slug'       => $this->string(255)->comment('Слаг'),
            'image'      => $this->string()->comment('картинка'),
            'status'     => $this->boolean()->defaultValue(true)->comment('Публиковать'),
            'sell_only'  => $this->boolean()->defaultValue(false)->comment('Только продажа'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment("Дата обновления"),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата создания'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product');
    }
}
