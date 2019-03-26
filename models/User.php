<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $ID
 * @property string $login
 * @property string $pass
 * @property string $EMail
 * @property string $Name
 * @property string $Patronymic
 * @property string $Surname
 * @property string $Personal_Phone
 * @property string $Birthday
 * @property int $status
 *
 * @property FsDevProcBreaking[] $fsDevProcBreakings
 * @property FsDevProcCall[] $fsDevProcCalls
 * @property FsDevProcCost[] $fsDevProcCosts
 * @property FsDevProcDelivery[] $fsDevProcDeliveries
 * @property FsDevProcProcess[] $fsDevProcProcesses
 * @property FsDevProcReception[] $fsDevProcReceptions
 * @property UsersStatusList $status0
 * @property UsersPortals[] $usersPortals
 * @property Portals[] $portals
 * @property UsersPortalsParametersValues[] $usersPortalsParametersValues
 * @property UsersTokens[] $usersTokens
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'pass', 'EMail', 'Name', 'Patronymic', 'Surname', 'Personal_Phone', 'Birthday'], 'required'],
            [['login', 'pass', 'EMail', 'Name', 'Patronymic', 'Surname', 'Personal_Phone'], 'string'],
            [['Birthday'], 'safe'],
            [['status'], 'integer'],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => UsersStatusList::className(), 'targetAttribute' => ['status' => 'ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'login' => 'Login',
            'pass' => 'Pass',
            'EMail' => 'E Mail',
            'Name' => 'Name',
            'Patronymic' => 'Patronymic',
            'Surname' => 'Surname',
            'Personal_Phone' => 'Personal Phone',
            'Birthday' => 'Birthday',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFsDevProcBreakings()
    {
        return $this->hasMany(FsDevProcBreaking::className(), ['Id_user' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFsDevProcCalls()
    {
        return $this->hasMany(FsDevProcCall::className(), ['Id_user' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFsDevProcCosts()
    {
        return $this->hasMany(FsDevProcCost::className(), ['Id_user' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFsDevProcDeliveries()
    {
        return $this->hasMany(FsDevProcDelivery::className(), ['Id_user' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFsDevProcProcesses()
    {
        return $this->hasMany(FsDevProcProcess::className(), ['Id_user' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFsDevProcReceptions()
    {
        return $this->hasMany(FsDevProcReception::className(), ['Id_user' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(UsersStatusList::className(), ['ID' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersPortals()
    {
        return $this->hasMany(UsersPortals::className(), ['User' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPortals()
    {
        return $this->hasMany(Portals::className(), ['NAME' => 'Portal'])->viaTable('users_portals', ['User' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersPortalsParametersValues()
    {
        return $this->hasMany(UsersPortalsParametersValues::className(), ['User' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersTokens()
    {
        return $this->hasMany(UsersTokens::className(), ['ID_User' => 'ID']);
    }
}
