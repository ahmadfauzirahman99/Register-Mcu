<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JenisMcu;

/**
 * JenisMcuSearch represents the model behind the search form of `app\models\JenisMcu`.
 */
class JenisMcuSearch extends JenisMcu
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jm_id'], 'integer'],
            [['jm_nama', 'jm_ket', 'jm_status'], 'safe'],
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
        $query = JenisMcu::find();

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
            'jm_id' => $this->jm_id,
        ]);

        $query->andFilterWhere(['like', 'jm_nama', $this->jm_nama])
            ->andFilterWhere(['like', 'jm_ket', $this->jm_ket])
            ->andFilterWhere(['like', 'jm_status', $this->jm_status]);

        return $dataProvider;
    }
}
