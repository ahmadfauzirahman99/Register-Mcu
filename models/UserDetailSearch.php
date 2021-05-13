<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserDetail;

/**
 * UserDetailSearch represents the model behind the search form of `app\models\UserDetail`.
 */
class UserDetailSearch extends UserDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user_detail'], 'integer'],
            [['no_rm', 'apakah_anda_anak_pertama', 'tanggal_pernikahan'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_user_detail' => $this->id_user_detail,
            'tanggal_pernikahan' => $this->tanggal_pernikahan,
        ]);

        $query->andFilterWhere(['like', 'no_rm', $this->no_rm])
            ->andFilterWhere(['like', 'apakah_anda_anak_pertama', $this->apakah_anda_anak_pertama]);

        return $dataProvider;
    }
}
