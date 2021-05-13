<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

class UserDaftarSearch extends UserDaftar
{
    public $submit_ujian;
    public function rules()
    {
        return [
            [['ud_id'], 'integer'],
            [['ud_nik', 'ud_nama', 'ud_email', 'ud_approve_status', 'ud_approve_ket', 'ud_is_pasien_baru'], 'string'],
            [['ud_tgl_lahir','ud_created_at'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = UserDaftar::find()->orderBy(['ud_id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'ud_is_pasien_baru' => $this->ud_is_pasien_baru,
        ]);
        $query->andFilterWhere(['like', 'ud_nik', $this->ud_nik])
            ->andFilterWhere(['like', 'ud_nama', $this->ud_nama])
            ->andFilterWhere(['like', 'ud_email', $this->ud_email])
            ->andFilterWhere(['like', 'ud_approve_status', $this->ud_approve_status])
            ->andFilterWhere(['like', 'ud_created_at', $this->ud_created_at])
            ->andFilterWhere(['like', 'ud_tgl_lahir', $this->ud_tgl_lahir != NULL ? date('Y-m-d', strtotime($this->ud_tgl_lahir)) : NULL]);
        return $dataProvider;
    }
}
