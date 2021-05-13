<?php
namespace app\models;
use Yii;
class Tarif extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'TARIF';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsimrs');
    }
    public static function primaryKey()
    {
        return ['Kd_Inst','Kd_SubInst'];
    }
}