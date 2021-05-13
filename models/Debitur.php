<?php
namespace app\models;
use Yii;
class Debitur extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'debitur';
    }
    public function rules()
    {
        return [
            [['d_kode'], 'string','max'=>10],
            [['d_nama'], 'string', 'max' => 255],
            [['d_status'],'string']
        ];
    }
    public function attributeLabels()
    {
        return [
            'd_kode' => 'Kode',
            'd_nama' => 'Nama',
            'd_status' => 'Status',
        ];
    }
    static function allAktif()
    {
        return self::find()->where(['d_status'=>'1'])->asArray()->all();
    }
}