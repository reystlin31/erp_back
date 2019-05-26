<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modules_users".
 *
 * @property string $Module
 * @property int $UserId
 * @property string $Status
 *
 * @property Modules $module
 * @property Users $user
 */
class ModuleUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modules_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Module', 'UserId'], 'required'],
            [['UserId'], 'integer'],
            [['Module'], 'string', 'max' => 21],
            [['Status'], 'string', 'max' => 30],
            [['Module', 'UserId'], 'unique', 'targetAttribute' => ['Module', 'UserId']],
            [['Module'], 'exist', 'skipOnError' => true, 'targetClass' => Modules::className(), 'targetAttribute' => ['Module' => 'Module']],
            [['UserId'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UserId' => 'ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Module' => 'Module',
            'UserId' => 'User ID',
            'Status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(Modules::className(), ['Module' => 'Module']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['ID' => 'UserId']);
    }
}
