<?php

use app\modules\admin\models\Operation;
use yii\db\Migration;

/**
 * Class m191031_175417_add_user_id_to_operation_table
 */
class m191031_175417_add_user_id_to_operation_table extends Migration {
    // Use up()/down() to run migration code without a transaction.
    public function up(){
        $this->addColumn(Operation::tableName(), 'user_id', $this->integer()->defaultValue(2)->after('type')->comment('Пользователь'));
    }

    public function down(){
        $this->dropColumn(Operation::tableName(), 'user_id');
    }
}
