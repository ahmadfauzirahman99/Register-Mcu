<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_detail".
 *
 * @property int $id_user_detail
 * @property string|null $no_rm
 * @property string|null $apakah_anda_anak_pertama
 * @property string|null $tanggal_pernikahan
 */
class UserDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['apakah_anda_anak_pertama'], 'string'],
            [['apakah_anda_anak_pertama'], 'required'],
            [['tanggal_pernikahan'], 'safe'],
            [['no_rm'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user_detail' => 'Id User Detail',
            'no_rm' => 'No Rm',
            'apakah_anda_anak_pertama' => 'Apakah Anda Anak Pertama',
            'tanggal_pernikahan' => 'Tanggal Pernikahan',
        ];
    }
}
