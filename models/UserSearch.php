<?php
namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;
class UserSearch extends User
{
    public $submit_ujian;
    public function rules()
    {
        return [
            [['u_upj_id','u_jenis_mcu_id','u_paket_id'],'integer'],
            [['u_alamat','u_no_hp','u_status','u_nama_petugas','u_rm','u_approve_status','u_is_pasien_baru'],'string'],
            [['u_nik','u_nama_depan','u_no_peserta','u_tgl_lahir'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = User::find()->where(['u_level'=>'2'])->orderBy(['u_nama_depan'=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if(!$this->validate()){
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'u_alamat', $this->u_alamat])
            ->andFilterWhere(['like', 'u_rm', $this->u_rm])
            ->andFilterWhere(['like', 'u_nama_petugas', $this->u_nama_petugas])
            ->andFilterWhere(['like', 'u_no_hp', $this->u_no_hp])
            ->andFilterWhere(['like', 'u_nama_depan', $this->u_nama_depan]);
        return $dataProvider;
    }
    public function searchByInstansi($params)
    {
        $query = User::find()->where(['u_level'=>'3'])->orderBy(['u_nama_depan'=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'u_status' => $this->u_status,
        ]);
        $query->andFilterWhere(['like', 'u_nik', $this->u_nik])
            ->andFilterWhere(['like', 'u_alamat', $this->u_alamat])
            ->andFilterWhere(['like', 'u_nama_depan', $this->u_nama_depan]);
        return $dataProvider;
    }
    public function searchByPermintaan($id,$params)
    {
        $query = User::find()->where(['u_level'=>'2'])->joinWith([
            'jadwalperiksa'=>function($q) use($id){
                $q->andWhere(['upj_up_id'=>$id])->orderBy(['upj_tgl'=>SORT_ASC]);
            }
        ])->with(['paket']);//->orderBy(['u_rm'=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'u_upj_id' => $this->u_upj_id,
            'u_paket_id' => $this->u_paket_id,
            'u_approve_status'=>$this->u_approve_status,
            'u_is_pasien_baru'=>$this->u_is_pasien_baru,
        ]);
        $query->andFilterWhere(['like', 'u_nik', $this->u_nik])
            ->andFilterWhere(['like', 'u_no_peserta', $this->u_no_peserta])
            ->andFilterWhere(['like', 'u_tgl_lahir', $this->u_tgl_lahir!=NULL ? date('Y-m-d',strtotime($this->u_tgl_lahir)) : NULL])
            ->andFilterWhere(['like', 'u_nama_depan', $this->u_nama_depan]);
        return $dataProvider;
    }
    function searchAsRiwayat($params,$nik=NULL)
    {
        $user=Yii::$app->user->identity;
        if(isset($user->ud_nik)){
            $nik=$user->ud_nik;
        }else{
            $nik=$nik;
        }
        $query = User::find()->where(['u_level'=>'2','u_nik'=>$nik]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'u_jenis_mcu_id', $this->u_jenis_mcu_id])
            ->andFilterWhere(['like', 'u_tgl_periksa', $this->u_tgl_periksa!=NULL ? date('Y-m-d',strtotime($this->u_tgl_periksa)) : NULL ]);
        return $dataProvider;
    }
}