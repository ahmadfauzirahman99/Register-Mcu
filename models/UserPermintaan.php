<?php
namespace app\models;
use Yii;
use app\widgets\App;
class UserPermintaan extends \yii\db\ActiveRecord
{
    public $up_paket_id;
    public static function tableName()
    {
        return 'user_permintaan';
    }
    public function rules()
    {
        return [
            [['up_nama','up_tgl_mulai','up_tgl_selesai','up_total_peserta','up_jenis_mcu_id'], 'required','on'=>'create','message'=>'{attribute} harus diisi'],
            [['up_status','up_paket_id'],'required','on'=>'update_status_by_dokter','message'=>'{attribute} harus diisi'],
            ['up_paket_id', 'each', 'rule' => ['integer'],'on'=>'update_status_by_dokter'],
            [['up_user_id', 'up_acc_by','up_total_peserta','up_debitur_id','up_jenis_mcu_id'], 'integer'],
            [['up_tgl_mulai', 'up_tgl_selesai', 'up_acc_at', 'up_updated_at', 'up_created_at'], 'safe'],
            [['up_status','up_status_ket'], 'string'],
            [['up_nama'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'up_id' => 'Up ID',
            'up_user_id' => 'Up User ID',
            'up_nama' => 'Judul Permintaan',
            'up_tgl_mulai' => 'Tgl Mulai Pemeriksaan',
            'up_tgl_selesai' => 'Tgl Selesai Pemeriksaan',
            'up_total_peserta'=>'Total Peserta',
            'up_debitur_id'=>'Jenis Debitur',
            'up_jenis_mcu_id'=>'Jenis Pemeriksaan',
            'up_status' => 'Status Pengajuan Pemeriksaan',
            'up_status_ket'=>'Keterangan Status Pemeriksaan',
            'up_acc_at' => 'Up Acc At',
            'up_acc_by' => 'Up Acc By',
            'up_updated_at' => 'Up Updated At',
            'up_created_at' => 'Up Created At',
            'up_paket_id'=>'Paket Pemeriksaan',
        ];
    }
    function beforeSave($model)
    {
        $this->up_tgl_mulai=date('Y-m-d',strtotime($this->up_tgl_mulai));
        $this->up_tgl_selesai=date('Y-m-d',strtotime($this->up_tgl_selesai));
        if(App::isInstansi()){
            $this->up_user_id=Yii::$app->user->identity->u_id;
        }
        if(App::isDokter() && $this->up_status==1){
            $this->up_acc_at=date('Y-m-d H:i:s');
            $this->up_acc_by=Yii::$app->user->identity->u_id;
        }
        if($this->isNewRecord){
            $this->up_created_at=date('Y-m-d H:i:s');
        }
        return parent::beforeSave($model);
    }
    function getUser()
    {
        return $this->hasOne(User::className(),['u_id'=>'up_user_id']);
    }
    function getJadwal()
    {
        return $this->hasMany(UserPermintaanJadwal::className(),['upj_up_id'=>'up_id']);
    }
    function getDebitur()
    {
        return $this->hasOne(Debitur::className(),['d_kode'=>'up_debitur_id']);
    }
    function getJenismcu()
    {
        return $this->hasOne(JenisMcu::className(),['jm_id'=>'up_jenis_mcu_id']);
    }
    function getPaketpemeriksaan()
    {
        return $this->hasMany(UserPermintaanPaket::className(),['upp_up_id'=>'up_id']);
    }
    function saveStatus()
    {
        if($this->save(false)){
            UserPermintaanPaket::deleteAll(['upp_up_id'=>$this->up_id]);
            $tmp=[];
            foreach($this->up_paket_id as $p){
                $tmp[]=[$this->up_id,$p,date('Y-m-d H:i:s')];
            }
            if(count($tmp)>0){
                Yii::$app->db->createCommand()->batchInsert(UserPermintaanPaket::tableName(), ['upp_up_id', 'upp_paket_id','upp_created_at'],$tmp)->execute();
            }
            return true;
        }
        return false;
    }
}
