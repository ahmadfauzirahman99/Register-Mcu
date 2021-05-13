<?php
namespace app\models;
use Yii;
class PaketTindakan extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'mcu.paket_tindakan_mcu';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbpost');
    }
    public function rules()
    {
        return [
            [['kode_paket','kode_tindakan', 'nama_tindakan', 'harga'], 'required','message'=>'{attribute} tidak boleh kosong'],
            [['harga','kode_paket'], 'integer'],
            [['nama_tindakan'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'kode_paket'=>'Paket',
            'kode_tindakan' => 'Kode Tindakan',
            'nama_tindakan' => 'Nama Tindakan',
            'harga' => 'Harga Paket',
        ];
    }
}