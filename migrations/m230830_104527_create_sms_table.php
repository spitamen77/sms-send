<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sms}}`.
 */
class m230830_104527_create_sms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sms}}', [
            'id' => $this->primaryKey(),
            'message_id' => $this->string()->defaultValue(null),
            'user_sms_id' => $this->integer()->defaultValue(null),
            'message' => $this->string()->notNull(),
            'phone_number' => $this->string()->notNull(),
            'sms_count' => $this->integer(),
            'status' => $this->string(),
            'status_date' => $this->dateTime()->defaultValue(null),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sms}}');
    }
}
