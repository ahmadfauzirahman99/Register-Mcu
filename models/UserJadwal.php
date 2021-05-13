<?php
namespace app\models;
use Yii;
use yii\helpers\Inflector;
class UserJadwal extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user_jadwal';
    }
	function getJadwal()
	{
		return $this->hasOne(Jadwal::className(),['j_id'=>'uj_jadwal_id']);
	}
}
