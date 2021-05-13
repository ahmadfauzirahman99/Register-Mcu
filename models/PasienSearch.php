<?php
namespace app\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Informasi;
class PasienSearch extends Pasien
{
    public function rules()
    {
        return [
            [['NO_PASIEN','NAMA','ALAMAT','NOIDENTITAS','TP_LAHIR','TGL_LAHIR','JENIS_KEL'],'string'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Pasien::find()->orderBy(['NO_PASIEN'=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if(!$this->validate()){
            return $dataProvider;
        }
        $query->andFilterWhere([
            'JENIS_KEL' => $this->JENIS_KEL,
        ]);
        $query->andFilterWhere(['like', 'NO_PASIEN', $this->NO_PASIEN])
            ->andFilterWhere(['like', 'NAMA', $this->NAMA])
            ->andFilterWhere(['like', 'NOIDENTITAS', $this->NOIDENTITAS])
            ->andFilterWhere(['like', 'ALAMAT', $this->ALAMAT])
            ->andFilterWhere(['like', 'CONVERT(VARCHAR(10),TGL_LAHIR,21)', $this->TGL_LAHIR!=NULL ? date('Y-m-d',strtotime($this->TGL_LAHIR))  : NULL]);

        return $dataProvider;
    }
}
