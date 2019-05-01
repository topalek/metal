<?php

use app\modules\admin\models\Operation;
use yii\db\Migration;

/**
 * Class m190429_173009_modify_created_field_operation_table
 */
class m190429_173009_modify_created_field_operation_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp(){
        $this->alterColumn(Operation::tableName(), "created_at", $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата создания'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(){
        $this->alterColumn(Operation::tableName(), 'created_at', $this->date()->comment('Дата создания'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190429_173009_modify_created_field_operation_table cannot be reverted.\n";

        return false;
    }
    */
}
