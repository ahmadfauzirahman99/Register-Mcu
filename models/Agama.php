<?php
namespace app\models;
use Yii;
class Agama extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'Agama';
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
