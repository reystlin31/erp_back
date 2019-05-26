<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fs_manufacturers".
 *
 * @property string $value Производитель
 */
class FsManufacturers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fs_manufacturers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'string', 'max' => 40],
            [['value'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'value' => 'Производитель',
        ];
    }
}
