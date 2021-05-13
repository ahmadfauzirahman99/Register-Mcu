<?php
namespace app\models;
use Yii;
use app\widgets\App;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserPermintaan;
class UserPermintaanSearch extends UserPermintaan
{
    public $instansi;
    public function rules()
    {
        return [
            [['up_id', 'up_user_id', 'up_acc_by','up_jenis_mcu_id'], 'integer'],
            [['instansi','up_nama', 'up_tgl_mulai', 'up_tgl_selesai', 'up_status', 'up_acc_at', 'up_updated_at', 'up_created_at'], 'safe'],
        ];
    }
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = UserPermintaan::find()->orderBy(['up_created_at'=>SORT_DESC]);
        if(App::isInstansi()){
            $query->where(['up_user_id'=>Yii::$app->user->identity->u_id]);
        }
        if(App::isDokter()){
            $query->joinWith(['user']);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'up_status' => $this->up_status,
            'up_jenis_mcu_id' => $this->up_jenis_mcu_id,
        ]);
        $query->andFilterWhere(['like', 'up_nama', $this->up_nama])
            ->andFilterWhere(['like', 'user.u_nama_depan', $this->instansi])
            ->andFilterWhere(['like', 'up_tgl_mulai', $this->up_tgl_mulai!=NULL ? date('Y-m-d',strtotime($this->up_tgl_mulai)) : NULL ])
            ->andFilterWhere(['like', 'up_tgl_selesai', $this->up_tgl_selesai!=NULL ? date('Y-m-d',strtotime($this->up_tgl_selesai)) : NULL ]);

        return $dataProvider;
    }
}
