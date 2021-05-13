<?php
namespace app\models;
use Yii;
class Pekerjaan extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'FMPEKERJAAN';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsimrs');
    }
    public function rules()
    {
        return [
            [['Id'],'integer'],
            [['PerkerjaanJabatan'], 'string','max'=>50],
            [['Nomor'], 'string', 'max' => 3],
        ];
    }
    public function attributeLabels()
    {
        return [
            'Id' => 'Jb ID',
            'PerkerjaanJabatan' => 'Jb Nama',
            'Nomor' => 'Jb Aktif',
        ];
    }
    static function all()
    {
        return self::find()->orderBy(['PerkerjaanJabatan'=>SORT_ASC])->asArray()->all();
    }
}
