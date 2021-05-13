<?php
namespace app\models;
use Yii;
class Berkas extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'berkas';
    }
    public function rules()
    {
        return [
            [['b_nama'], 'required'],
            [['b_status'], 'string'],
            [['b_nama'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'b_id' => 'Jb ID',
            'b_nama' => 'Jb Nama',
            'b_status' => 'Jb Aktif',
        ];
    }
    static function all()
    {
        return self::find()->where(['jb_aktif'=>'1'])->asArray()->all();
    }
    function getUserberkas()
    {
        return $this->hasOne(UserBerkas::className(),['ub_berkas_id'=>'b_id']);
    }
}
