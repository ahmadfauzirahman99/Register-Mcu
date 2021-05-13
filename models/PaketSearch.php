<?php
namespace app\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Paket;
class PaketSearch extends Paket
{
    public function rules()
    {
        return [
            [['kode'], 'integer'],
            [['nama', 'is_active', 'kode_debitur', 'jenis_paket'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Paket::find()->orderBy(['nama'=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'is_active' => $this->is_active,
            'jenis_paket' => $this->jenis_paket,
        ]);
        $query->andFilterWhere(['ilike', 'nama', $this->nama]);
        return $dataProvider;
    }
}