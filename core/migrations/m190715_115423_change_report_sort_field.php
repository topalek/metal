<?php

use app\modules\admin\models\Product;
use yii\db\Migration;

/**
 * Class m190715_115423_change_report_sort_field
 */
class m190715_115423_change_report_sort_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn(Product::tableName(), 'report_sort', 'use_form');
        $this->alterColumn(Product::tableName(), 'use_form', $this->boolean()->defaultValue(0)->comment("Использовалать форму для продажи"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn(Product::tableName(), 'use_form', $this->integer(4)->after('status')->defaultValue(1)->comment("Сортировка для отчета"));
        $this->renameColumn(Product::tableName(), 'use_form', 'report_sort');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190715_115423_change_report_sort_fild cannot be reverted.\n";

        return false;
    }
    */
}
