<?php
namespace app\models;
use Yii;
class Paket extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'mcu.paket';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbpost');
    }
    public function rules()
    {
        return [
            [['nama', 'is_active', 'jenis_paket'], 'required','message'=>'{attribute} tidak boleh kosong'],
            [['kode'], 'integer'],
            [['jenis_paket'],'safe'],
            [['nama'], 'string', 'max' => 100],
        ];
    }
    public function attributeLabels()
    {
        return [
            'kode' => 'ID',
            'nama' => 'Nama Paket',
            'is_active' => 'Status Paket',
            'jenis_paket' => 'Jenis Paket',
        ];
    }
    function getTindakan()
    {
        return $this->hasMany(PaketTindakan::className(),['kode_paket'=>'kode']);
    }
}