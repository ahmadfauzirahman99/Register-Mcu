<?php
namespace app\models;
use Yii;
class Provinsi extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'FMPROVINSI';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsimrs');
    }
    public function rules()
    {
        return [
            [['id'],'integer'],
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
