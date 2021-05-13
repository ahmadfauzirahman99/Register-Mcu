<?php
namespace app\models;
use Yii;
class VWRAWATINAP extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'VW_RAWATINAP';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsimrs');
    }
    public static function primaryKey()
    {
        return ['NO_DAFTAR','NO_PASIEN'];
    }
    
}