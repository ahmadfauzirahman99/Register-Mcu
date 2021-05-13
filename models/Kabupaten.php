<?php
namespace app\models;
use Yii;
class Kabupaten extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'FMKOTA';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsimrs');
    }
    public function rules()
    {
        return [
            [['id','id_fmprovinsi'],'integer'],
            [['nama'], 'string','max'=>255],
            [['idlama'], 'string', 'max' => 50],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'Jb ID',
            'nama' => 'Jb Nama',
            'idlama' => 'Jb Aktif',
        ];
    }
}
