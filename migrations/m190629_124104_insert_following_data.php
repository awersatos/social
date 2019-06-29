<?php

use yii\db\Migration;

/**
 * Class m190629_124104_insert_following_data
 */
class m190629_124104_insert_following_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        for($i = 1; $i < 1001; $i++){
            $items = [];
            $followersNumber = ($i == 500) ? 100 : 20;
            for($j = 0; $j < $followersNumber; $j++){
                $follower = rand(1, 1000);
                    if($follower == $j){
                    continue;
                    }
                $items[$j] = [$i, $follower];
            }
            $this->batchInsert('following',['user_id', 'follower_id'], $items);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190629_124104_insert_following_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190629_124104_insert_following_data cannot be reverted.\n";

        return false;
    }
    */
}
