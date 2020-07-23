<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m200722_195929_add_verification_and_password_reset_fields_to_user_table
 */
class m200722_195929_add_verification_and_password_reset_fields_to_user_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn(User::tableName(), 'password_reset_token', $this->string()->unique());
        $this->addColumn(User::tableName(), 'verification_token', $this->string()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn(User::tableName(), 'verification_token');
        $this->dropColumn(User::tableName(), 'password_reset_token');
    }
}
