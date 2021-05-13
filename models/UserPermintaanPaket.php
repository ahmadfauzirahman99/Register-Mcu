<?php
namespace app\models;
use Yii;
class UserPermintaanPaket extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user_permintaan_paket';
    }
    public function rules()
    {
        return [
            [['upp_up_id', 'upp_paket_id'], 'required'],
            [['upp_up_id', 'upp_paket_id'], 'integer'],
            [['upp_created_at'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'upp_id' => 'Upp ID',
            'upp_up_id' => 'Upp Up ID',
            'upp_paket_id' => 'Upp Paket ID',
            'upp_created_at' => 'Upp Created At',
        ];
    }
    function getPaket()
    {
        return $this->hasOne(Paket::className(),['kode'=>'upp_paket_id']);
    }
}
