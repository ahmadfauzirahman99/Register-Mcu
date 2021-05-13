<?php
namespace app\models;
use Yii;
class Antrian extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'AntrianKlinikNomorUrut';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsimrs');
    }
		
}