<?php
namespace app\models;
use Yii;
class Kecamatan extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'KECAMATAN';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsimrs');
    }
}
