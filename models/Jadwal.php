<?php
namespace app\models;
use Yii;
class Jadwal extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'jadwal';
    }
    public function rules()
    {
        return [
            [['f_jenis_pendidikan_id', 'f_nama_formasi', 'f_pendidikan'], 'required'],
            [['j_kuota'], 'integer'],
            [['j_tgl'],'safe'],
            [['j_hari'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'j_id' => 'F ID',
            'j_tgl' => 'F Jenis Pendidikan ID',
            'j_hari' => 'F Nama Formasi',
            'j_kuota' => 'F Pendidikan',
        ];
    }
}
