<?php
namespace app\models;
use Yii;
class Informasi extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'informasi';
    }
    public function rules()
    {
        return [
            [['i_info','i_jenis','i_status'],'required','on'=>'create','message'=>'{attribute} harus diisi'],
            [['i_urut'],'integer'],
            [['i_info','i_jenis','i_status'], 'string'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'i_id' => 'Info ID',
            'i_info' => 'Informasi',
            'i_jenis'=>'Jenis Informasi',
            'i_status'=>'Status Informasi',
            'i_urut'=>'No. Urut'
        ];
    }
}