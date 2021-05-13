<?php
namespace app\models;
use Yii;
class Setting extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'setting';
    }
    public function rules()
    {
        return [
            [['set_kode', 'set_value'], 'string'],
            [['set_updated_at'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'set_kode' => 'Kode',
            'set_value' => 'Value',
            'set_updated_at' => 'Tanggal Update',
        ];
    }
}