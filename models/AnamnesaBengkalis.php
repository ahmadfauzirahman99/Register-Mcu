<?php
namespace app\models;
use Yii;
class AnamnesaBengkalis extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'mcu.anamnesa_bengkalis';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbpost');
    }
}