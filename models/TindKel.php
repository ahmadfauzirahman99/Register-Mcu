<?php
namespace app\models;
use Yii;
class TindKel extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'TindKel';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsimrs');
    }
    static function all()
    {
        return self::find()->asArray()->all();
    }
}
