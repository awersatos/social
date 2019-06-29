<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%following}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m190629_111630_create_following_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%following}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'follower_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-following-user_id}}',
            '{{%following}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-following-user_id}}',
            '{{%following}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `follower_id`
        $this->createIndex(
            '{{%idx-following-follower_id}}',
            '{{%following}}',
            'follower_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-following-follower_id}}',
            '{{%following}}',
            'follower_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-following-user_id}}',
            '{{%following}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-following-user_id}}',
            '{{%following}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-following-follower_id}}',
            '{{%following}}'
        );

        // drops index for column `follower_id`
        $this->dropIndex(
            '{{%idx-following-follower_id}}',
            '{{%following}}'
        );

        $this->dropTable('{{%following}}');
    }
}
