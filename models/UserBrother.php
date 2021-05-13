<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_brother".
 *
 * @property int $id_user_brother
 * @property string $nik
 * @property string|null $nama_lengkap_saudara_sekandung
 * @property string $hubungan_persaudaran
 * @property string|null $jenis_kelamin
 */
class UserBrother extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_brother';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nik', 'hubungan_persaudaran'], 'required'],
            [['jenis_kelamin'], 'string'],
            [['nik', 'nama_lengkap_saudara_sekandung', 'hubungan_persaudaran'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user_brother' => 'Id User Brother',
            'nik' => 'Nik',
            'nama_lengkap_saudara_sekandung' => 'Nama Lengkap Saudara Sekandung',
            'hubungan_persaudaran' => 'Hubungan Persaudaran',
            'jenis_kelamin' => 'Jenis Kelamin',
        ];
    }
}
