<?php

use yii\db\Migration;

/**
 * Class m190629_143542_insert_post_data
 */
class m190629_143542_insert_post_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        for ($i = 0; $i < 8000; $i++) {
            if(($i % 50) == 0){
                $id = 500;
            } else {
                $id = rand(1, 1000);
            }

            $timestamp = time();

            $this->insert('post',[
                'user_id' => $id,
                'timestamp' => $timestamp,
                'message' => 'Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне.'
            ]);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190629_143542_insert_post_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190629_143542_insert_post_data cannot be reverted.\n";

        return false;
    }
    */
}
