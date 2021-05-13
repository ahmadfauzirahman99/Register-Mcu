<?php
namespace app\models;
use Yii;
class UserKuisioner extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user_kuisioner';
    }
    public function rules()
    {
        return [
            [['u_id', 'k_id'], 'required'],
            [['u_id', 'k_id'], 'integer'],
            [['uk_ceklis', 'uk_keterangan'], 'string'],
            [['uk_updated_at', 'uk_created_at'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'uk_id' => 'Uk ID',
            'u_id' => 'U ID',
            'k_id' => 'K ID',
            'uk_ceklis' => 'Uk Ceklis',
            'uk_keterangan' => 'Uk Keterangan',
            'uk_updated_at' => 'Uk Updated At',
            'uk_created_at' => 'Uk Created At',
        ];
    }
    function getKuisioner()
    {
        return $this->hasOne(Kuisioner::className(),['k_id'=>'k_id']);
    }
}