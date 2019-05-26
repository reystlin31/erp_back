<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fs_types_device".
 *
 * @property string $Type Тип изделия
 *
 * @property FsManufacturerModel[] $fsManufacturerModels
 */
class FsTypesDevice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fs_types_device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'string', 'max' => 30],
            [['value'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'value' => 'Тип изделия',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFsManufacturerModels()
    {
        return $this->hasMany(FsManufacturerModel::className(), ['Type' => 'value']);
    }
}
