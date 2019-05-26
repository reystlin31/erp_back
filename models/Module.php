<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modules".
 *
 * @property string $Module
 * @property string $Name
 *
 * @property ModulesUsers[] $modulesUsers
 * @property Users[] $users
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Module', 'Name'], 'required'],
            [['Name'], 'string'],
            [['Module'], 'string', 'max' => 21],
            [['Module'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Module' => 'Module',
            'Name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModulesUsers()
    {
        return $this->hasMany(ModulesUsers::className(), ['Module' => 'Module']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['ID' => 'UserId'])->viaTable('modules_users', ['Module' => 'Module']);
    }
}
