<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sms".
 *
 * @property int $id
 * @property string|null $message_id
 * @property int|null $user_sms_id
 * @property string $message
 * @property string $phone_number
 * @property int|null $sms_count
 * @property string|null $status
 * @property string|null $status_date
 * @property string $created_at
 * @property string|null $updated_at
 */
class Sms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_sms_id', 'sms_count'], 'integer'],
            [['message', 'phone_number'], 'required'],
            [['status_date', 'created_at', 'updated_at'], 'safe'],
            [['message_id', 'message', 'phone_number', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_id' => 'Message ID',
            'user_sms_id' => 'User Sms ID',
            'message' => 'Xabar',
            'phone_number' => 'Tel nomer',
            'sms_count' => 'Sms Count',
            'status' => 'Status',
            'status_date' => 'Status Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
