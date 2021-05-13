<?php
namespace app\models;
use Yii;
class Mirai extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'daftar';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbmirai');
    }
    static function all()
    {
        return self::find()->asArray()->all();
    }
}
