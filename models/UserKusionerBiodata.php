<?php
namespace app\models;
use Yii;
class UserKusionerBiodata extends \yii\db\ActiveRecord
{
    public $is_sebelum,$is_sekarang,$is_dituju;
    public static function tableName()
    {
        return 'user_kusioner_biodata';
    }
    public function rules()
    {
        return [
            [['ukb_user_id'], 'required'],
            [['ukb_user_id'], 'integer'],
            [['ukb_sblm_utama_uraian', 'ukb_sblm_utama_target', 'ukb_sblm_utama_cara', 'ukb_sblm_utama_alat', 'ukb_sblm_tambah_uraian', 'ukb_sblm_tambah_target', 'ukb_sblm_tambah_cara', 'ukb_sblm_tambah_alat', 'ukb_skrg_utama_uraian', 'ukb_skrg_utama_target', 'ukb_skrg_utama_cara', 'ukb_skrg_utama_alat', 'ukb_skrg_tambah_uraian', 'ukb_skrg_tambah_target', 'ukb_skrg_tambah_cara', 'ukb_skrg_tambah_alat', 'ukb_dituju_utama_uraian', 'ukb_dituju_utama_target', 'ukb_dituju_utama_cara', 'ukb_dituju_utama_alat', 'ukb_dituju_tambah_uraian', 'ukb_dituju_tambah_target', 'ukb_dituju_tambah_cara', 'ukb_dituju_tambah_alat'], 'string'],
            [['is_sebelum','is_sekarang','is_dituju'],'string','max'=>1],
            [['ukb_updated_at', 'ukb_created_at'], 'safe'],
            [['ukb_krj_sebelum', 'ukb_krj_sebelum_perusahaan', 'ukb_krj_skrg', 'ukb_krj_skrg_perusahaan', 'ukb_krj_dituju', 'ukb_krj_dituju_perusahaan'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'ukb_id' => 'Ukb ID',
            'ukb_user_id' => 'Ukb User ID',
            'ukb_krj_sebelum' => 'Pekerjaan Sebelumnya',
            'ukb_krj_sebelum_perusahaan'=>'Perusahaan Sebelumnya',
            'ukb_krj_skrg' => 'Pekerjaan Sekarang',
            'ukb_krj_skrg_perusahaan'=>'Perusahaan Sekarang',
            'ukb_krj_dituju' => 'Pekerjaan Dituju',
            'ukb_krj_dituju_perusahaan'=>'Perusahaan Dituju',
            'ukb_sblm_utama_uraian' => 'Ukb Sblm Utama Uraian',
            'ukb_sblm_utama_target' => 'Ukb Sblm Utama Target',
            'ukb_sblm_utama_cara' => 'Ukb Sblm Utama Cara',
            'ukb_sblm_utama_alat' => 'Ukb Sblm Utama Alat',
            'ukb_sblm_tambah_uraian' => 'Ukb Sblm Tambah Uraian',
            'ukb_sblm_tambah_target' => 'Ukb Sblm Tambah Target',
            'ukb_sblm_tambah_cara' => 'Ukb Sblm Tambah Cara',
            'ukb_sblm_tambah_alat' => 'Ukb Sblm Tambah Alat',
            'ukb_skrg_utama_uraian' => 'Ukb Skrg Utama Uraian',
            'ukb_skrg_utama_target' => 'Ukb Skrg Utama Target',
            'ukb_skrg_utama_cara' => 'Ukb Skrg Utama Cara',
            'ukb_skrg_utama_alat' => 'Ukb Skrg Utama Alat',
            'ukb_skrg_tambah_uraian' => 'Ukb Skrg Tambah Uraian',
            'ukb_skrg_tambah_target' => 'Ukb Skrg Tambah Target',
            'ukb_skrg_tambah_cara' => 'Ukb Skrg Tambah Cara',
            'ukb_skrg_tambah_alat' => 'Ukb Skrg Tambah Alat',
            'ukb_dituju_utama_uraian' => 'Ukb Dituju Utama Uraian',
            'ukb_dituju_utama_target' => 'Ukb Dituju Utama Target',
            'ukb_dituju_utama_cara' => 'Ukb Dituju Utama Cara',
            'ukb_dituju_utama_alat' => 'Ukb Dituju Utama Alat',
            'ukb_dituju_tambah_uraian' => 'Ukb Dituju Tambah Uraian',
            'ukb_dituju_tambah_target' => 'Ukb Dituju Tambah Target',
            'ukb_dituju_tambah_cara' => 'Ukb Dituju Tambah Cara',
            'ukb_dituju_tambah_alat' => 'Ukb Dituju Tambah Alat',
            'ukb_updated_at' => 'Ukb Updated At',
            'ukb_created_at' => 'Ukb Created At',
        ];
    }
    function beforeSave($model)
    {
        if($this->is_sebelum=='n'){
            $this->ukb_krj_sebelum=NULL;
            $this->ukb_krj_sebelum_perusahaan=NULL;
            $this->ukb_sblm_utama_uraian=NULL;
            $this->ukb_sblm_utama_target=NULL;
            $this->ukb_sblm_utama_cara=NULL;
            $this->ukb_sblm_utama_alat=NULL;
            $this->ukb_sblm_tambah_uraian=NULL;
            $this->ukb_sblm_tambah_target=NULL;
            $this->ukb_sblm_tambah_cara=NULL;
            $this->ukb_sblm_tambah_alat=NULL;
        }
        if($this->is_sekarang=='n'){
            $this->ukb_krj_skrg=NULL;
            $this->ukb_krj_skrg_perusahaan=NULL;
            $this->ukb_skrg_utama_uraian=NULL;
            $this->ukb_skrg_utama_target=NULL;
            $this->ukb_skrg_utama_cara=NULL;
            $this->ukb_skrg_utama_alat=NULL;
            $this->ukb_skrg_tambah_uraian=NULL;
            $this->ukb_skrg_tambah_target=NULL;
            $this->ukb_skrg_tambah_cara=NULL;
            $this->ukb_skrg_tambah_alat=NULL;
        }
        if($this->is_dituju=='n'){
            $this->ukb_krj_dituju=NULL;
            $this->ukb_krj_dituju_perusahaan=NULL;
            $this->ukb_dituju_utama_uraian=NULL;
            $this->ukb_dituju_utama_target=NULL;
            $this->ukb_dituju_utama_cara=NULL;
            $this->ukb_dituju_utama_alat=NULL;
            $this->ukb_dituju_tambah_uraian=NULL;
            $this->ukb_dituju_tambah_target=NULL;
            $this->ukb_dituju_tambah_cara=NULL;
            $this->ukb_dituju_tambah_alat=NULL;
        }
        return parent::beforeSave($model);
    }
    function saveData($data)
    {
        $user=Yii::$app->user->identity;
        if(isset($user->ud_id)){
            $daftar=User::getLatest($user->ud_nik);
            $id=$daftar['u_id'];
        }else{
            $id=Yii::$app->user->identity->u_id;
        }
        $pilihan=$data['riwayat-pilihan'];
        $jelaskan=isset($data['riwayat-jelaskan']) ? $data['riwayat-jelaskan'] : NULL;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $this->save(false);
            if(count($pilihan)>0){
                $db->createCommand("
                    delete uk from ".UserKuisioner::tableName()." uk
                    inner join ".Kuisioner::tableName()." k on k.k_id=uk.k_id
                    inner join ".KategoriKuisioner::tableName()." kk on kk.kk_id=k.kk_id
                    where u_id = :user and kk.kk_id = 4
                ")->bindValues([':user'=>$this->ukb_user_id])->execute();
                $tmp=[];
                foreach($pilihan as $k => $d){
                    $tmp[]=[$this->ukb_user_id,$k,$pilihan[$k],isset($jelaskan[$k]) ? $jelaskan[$k] : NULL,date('Y-m-d H:i:s')];
                }
            }
            $db->createCommand()->batchInsert(UserKuisioner::tableName(),['u_id','k_id','uk_ceklis','uk_keterangan','uk_created_at'],$tmp)->execute();
            $db->createCommand()->update(User::tableName(), ['u_kuisioner1_finish_at' =>date('Y-m-d H:i:s')],['u_id'=>$id])->execute();
            $transaction->commit();
            return true;
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    function saveRiwayatPenyakit($data)
    {
        $user=Yii::$app->user->identity;
        if(isset($user->ud_id)){
            $daftar=User::getLatest($user->ud_nik);
            $id=$daftar['u_id'];
        }else{
            $id=Yii::$app->user->identity->u_id;
        }
        $pilihan=$data['riwayat-pilihan'];
        $jelaskan=array_filter($data['riwayat-jelaskan'],function($q){
            return $q!=NULL;
        });
        $ary=array_replace_recursive($pilihan,$jelaskan);
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $tmp=[];
            if(count($pilihan)>0){
                $db->createCommand("
                    delete uk from ".UserKuisioner::tableName()." uk
                    inner join ".Kuisioner::tableName()." k on k.k_id=uk.k_id
                    inner join ".KategoriKuisioner::tableName()." kk on kk.kk_id=k.kk_id
                    where u_id = :user and kk.kk_id != 4
                ")->bindValues([':user'=>$id])->execute();
                foreach($ary as $k => $d){
                    if(($k>=46 && $k<=53) || $k==57 || $k==9){
                        if(isset($jelaskan[$k])){
                            $tmp[]=[$id,$k,NULL,isset($jelaskan[$k]) ? $jelaskan[$k] : NULL,date('Y-m-d H:i:s')];
                        }
                    }else{
                        $tmp[]=[$id,$k,isset($pilihan[$k]) ? $pilihan[$k] : NULL ,isset($jelaskan[$k]) ? $jelaskan[$k] : NULL,date('Y-m-d H:i:s')];
                    }
                }
            }
            $db->createCommand()->batchInsert(UserKuisioner::tableName(),['u_id','k_id','uk_ceklis','uk_keterangan','uk_created_at'],$tmp)->execute();
            $db->createCommand()->update(User::tableName(), ['u_kuisioner2_finish_at' =>date('Y-m-d H:i:s')],['u_id'=>$id])->execute();
            $transaction->commit();
            return true;
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    function saveRiwayatCpns($data)
    {
        $user=Yii::$app->user->identity;
        if(isset($user->ud_id)){
            $daftar=User::getLatest($user->ud_nik);
            $id=$daftar['u_id'];
        }else{
            $id=$user->u_id;
        }
        $pilihan=$data['riwayat-pilihan'];
        $jelaskan=[];
        if(isset($data['riwayat-jelaskan'])){
            $jelaskan=array_filter($data['riwayat-jelaskan'],function($q){
                return $q!=NULL;
            });
        }
        $ary=array_replace_recursive($pilihan,$jelaskan);
        // echo "<pre>";
        // print_r($pilihan);
        // print_r($jelaskan);
        // print_r($ary);
        // exit;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $tmp=[];
            if(count($pilihan)>0){
                $db->createCommand("
                    delete uk from ".UserKuisioner::tableName()." uk
                    inner join ".Kuisioner::tableName()." k on k.k_id=uk.k_id
                    inner join ".KategoriKuisioner::tableName()." kk on kk.kk_id=k.kk_id
                    where u_id = :user and kk.kk_id = 5
                ")->bindValues([':user'=>$id])->execute();
                foreach($ary as $k => $d){
                        $tmp[]=[$id,$k,isset($pilihan[$k]) ? $pilihan[$k] : NULL ,isset($jelaskan[$k]) ? $jelaskan[$k] : NULL,date('Y-m-d H:i:s')];
                }
            }
            // echo "<pre>"; print_r($tmp); exit;
            $db->createCommand()->batchInsert(UserKuisioner::tableName(),['u_id','k_id','uk_ceklis','uk_keterangan','uk_created_at'],$tmp)->execute();
            $db->createCommand()->update(User::tableName(), ['u_kuisioner3_finish_at' =>date('Y-m-d H:i:s')],['u_id'=>$id])->execute();
            $transaction->commit();
            return true;
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
