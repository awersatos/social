<?php

use yii\db\Migration;

/**
 * Class m190629_114614_insert_user_data
 */
class m190629_114614_insert_user_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $users = [];
        for ($i = 0; $i < 1000; $i++) {
            $users[$i] = ["User-$i", 'test'];
        }

        $this->batchInsert('user', ['name', 'password'], $users);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m190629_114614_insert_user_data cannot be reverted.\n";

        $this->delete('user');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190629_114614_insert_user_data cannot be reverted.\n";

        return false;
    }
    */
}
