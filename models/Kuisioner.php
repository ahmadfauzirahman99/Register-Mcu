<?php
namespace app\models;
use Yii;
class Kuisioner extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'kuisioner';
    }
    public function rules()
    {
        return [
            [['kk_id', 'k_isi_indo'], 'required'],
            [['kk_id', 'k_id_parent'], 'integer'],
            [['k_isi_indo', 'k_isi_eng', 'k_tipe'], 'string'],
            [['k_updated_at', 'k_created_at'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'k_id' => 'K ID',
            'kk_id' => 'Kk ID',
            'k_id_parent' => 'K Id Parent',
            'k_isi_indo' => 'K Isi Indo',
            'k_isi_eng' => 'K Isi Eng',
            'k_tipe' => 'K Tipe',
            'k_updated_at' => 'K Updated At',
            'k_created_at' => 'K Created At',
        ];
    }
    function getKategori()
    {
        return $this->hasOne(KategoriKuisioner::className(),['kk_id'=>'kk_id']);
    }
    function getUserkuisioner()
    {
        return $this->hasOne(UserKuisioner::className(),['k_id'=>'k_id']);
    }
}
