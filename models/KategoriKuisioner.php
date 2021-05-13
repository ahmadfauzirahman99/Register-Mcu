<?php
namespace app\models;
use Yii;
class KategoriKuisioner extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'kategori_kuisioner';
    }
    public function rules()
    {
        return [
            [['kk_id', 'kk_nama_indo'], 'required'],
            [['kk_id'], 'integer'],
            [['ub_updated_at', 'ub_created_at'], 'safe'],
            [['kk_nama_indo', 'kk_nama_eng'], 'string', 'max' => 255],
            [['kk_id'], 'unique'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'kk_id' => 'Kk ID',
            'kk_nama_indo' => 'Kk Nama Indo',
            'kk_nama_eng' => 'Kk Nama Eng',
            'ub_updated_at' => 'Ub Updated At',
            'ub_created_at' => 'Ub Created At',
        ];
    }
    function getKuisioner()
    {
        return $this->hasMany(Kuisioner::className(),['kk_id'=>'kk_id']);
    }
}
