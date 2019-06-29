<?php

use yii\db\Migration;
use \yii\db\Query;

/**
 * Class m190629_133236_update_user_data
 */
class m190629_133236_update_user_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        for ($i = 1; $i < 1001; $i++) {

            $following = (new Query())
                ->select('user_id')
                ->from('following')
                ->where(['follower_id' => $i])
                ->all();
            $followed = [];
            foreach ($following as $item) {
                $followed[] = (int)$item['user_id'];
            }
            $followed = serialize($followed);
            $followersNumber = ($i == 500) ? 100 : 20;

            $this->update('user', ['followers' => $followersNumber, 'followed' => $followed], "id=$i");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190629_133236_update_user_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190629_133236_update_user_data cannot be reverted.\n";

        return false;
    }
    */
}
