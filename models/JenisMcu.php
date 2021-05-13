<?php
namespace app\models;
use Yii;
class JenisMcu extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'jenis_mcu';
    }
    public function rules()
    {
        return [
            [['jm_ket'], 'required'],
            [['jm_ket', 'jm_status'], 'string'],
            [['jm_nama'], 'string', 'max' => 100],
        ];
    }
    public function attributeLabels()
    {
        return [
            'jm_id' => 'Jm ID',
            'jm_nama' => 'Nama Jenis MCU',
            'jm_ket' => 'Keterangan Jenis MCU',
            'jm_status' => 'Status Jenis MCU',
        ];
    }
}
