<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fs_manufacturer_model".
 *
 * @property string $Manufacturer Производитель
 * @property string $Model Модель
 * @property string $Type Тип изделия
 * @property int $Cost Средняя стоимость нового
 *
 * @property FsTypesDevice $type
 */
class FsManufacturerModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fs_manufacturer_model';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Manufacturer', 'Model', 'Type', 'Cost'], 'required'],
            [['Cost'], 'integer'],
            [['Manufacturer', 'Model'], 'string', 'max' => 21],
            [['Type'], 'string', 'max' => 30],
            [['Manufacturer', 'Model'], 'unique', 'targetAttribute' => ['Manufacturer', 'Model']],
            [['Type'], 'exist', 'skipOnError' => true, 'targetClass' => FsTypesDevice::className(), 'targetAttribute' => ['Type' => 'Type']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Manufacturer' => 'Производитель',
            'Model' => 'Модель',
            'Type' => 'Тип изделия',
            'Cost' => 'Средняя стоимость нового',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(FsTypesDevice::className(), ['Type' => 'Type']);
    }
}
