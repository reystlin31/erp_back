<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_tokens".
 *
 * @property string $Token
 * @property int $ID_User
 * @property string $Create_Date
 * @property string $Last_Visit
 *
 * @property User $user
 */
class UserToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Token', 'ID_User', 'Create_Date', 'Last_Visit'], 'required'],
            [['ID_User'], 'integer'],
            [['Create_Date', 'Last_Visit'], 'safe'],
            [['Token'], 'string', 'max' => 32],
            [['Token'], 'unique'],
            [['ID_User'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['ID_User' => 'ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Token' => 'Token',
            'ID_User' => 'I D User',
            'Create_Date' => 'Create Date',
            'Last_Visit' => 'Last Visit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['ID' => 'ID_User']);
    }
}
