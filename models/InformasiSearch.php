<?php
namespace app\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Informasi;
class InformasiSearch extends Informasi
{
    public function rules()
    {
        return [
            [['i_id'], 'integer'],
            [['i_info', 'i_jenis', 'i_urut','i_status'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Informasi::find()->orderBy(['i_jenis'=>SORT_ASC,'i_urut'=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'i_jenis' => $this->i_jenis,
            'i_status' => $this->i_status,
        ]);
        $query->andFilterWhere(['like', 'i_info', $this->i_info]);

        return $dataProvider;
    }
}
