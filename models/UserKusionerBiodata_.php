<?php
namespace app\models;
use Yii;
class UserKusionerBiodata_ extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user_kusioner_biodata';
    }
    public function rules()
    {
        return [
            [['ukb_user_id'], 'required'],
            [['ukb_user_id'], 'integer'],
            [['ukb_krj_sebelum_perusahaan','ukb_krj_skrg_perusahaan','ukb_krj_dituju_perusahaan','ukb_sblm_utama_uraian', 'ukb_sblm_utama_target', 'ukb_sblm_utama_cara', 'ukb_sblm_utama_alat', 'ukb_sblm_tambah_uraian', 'ukb_sblm_tambah_target', 'ukb_sblm_tambah_cara', 'ukb_sblm_tambah_alat', 'ukb_skrg_uraian', 'ukb_skrg_target', 'ukb_skrg_cara', 'ukb_skrg_alat', 'ukb_dituju_uraian', 'ukb_dituju_target', 'ukb_dituju_cara', 'ukb_dituju_alat'], 'string'],
            [['ukb_krj_sebelum', 'ukb_krj_skrg', 'ukb_krj_dituju'], 'string', 'max' => 255],
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
            'ukb_utama_uraian' => 'Ukb Utama Uraian',
            'ukb_utama_target' => 'Ukb Utama Target',
            'ukb_utama_cara' => 'Ukb Utama Cara',
            'ukb_utama_alat' => 'Ukb Utama Alat',
            'ukb_tambah_uraian' => 'Ukb Tambah Uraian',
            'ukb_tambah_target' => 'Ukb Tambah Target',
            'ukb_tambah_cara' => 'Ukb Tambah Cara',
            'ukb_tambah_alat' => 'Ukb Tambah Alat',
            'ukb_skrg_uraian' => 'Uraian Pekerjaan',
            'ukb_skrg_target' => 'Target Kerja',
            'ukb_skrg_cara' => 'Cara Kerja',
            'ukb_skrg_alat' => 'Alat Kerja',
            'ukb_dituju_uraian' => 'Uraian Pekerjaan',
            'ukb_dituju_target' => 'Target Kerja',
            'ukb_dituju_cara' => 'Cara Kerja',
            'ukb_dituju_alat' => 'Alat Kerja',
        ];
    }
    function saveData($data)
    {
        $pilihan=$data['riwayat-pilihan'];
        $jelaskan=$data['riwayat-jelaskan'];
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
                    $tmp[]=[$this->ukb_user_id,$k,$pilihan[$k],$jelaskan[$k],date('Y-m-d H:i:s')];
                }
            }
            $db->createCommand()->batchInsert(UserKuisioner::tableName(),['u_id','k_id','uk_ceklis','uk_keterangan','uk_created_at'],$tmp)->execute();
            $db->createCommand()->update(User::tableName(), ['u_kuisioner1_finish_at' =>date('Y-m-d H:i:s')],['u_id'=>Yii::$app->user->identity->u_id])->execute();
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
        $user_id=Yii::$app->user->identity->u_id;
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
                ")->bindValues([':user'=>$user_id])->execute();
                foreach($ary as $k => $d){
                    if(($k>=46 && $k<=53) || $k==57 || $k==9){
                        if(isset($jelaskan[$k])){
                            $tmp[]=[$user_id,$k,NULL,isset($jelaskan[$k]) ? $jelaskan[$k] : NULL,date('Y-m-d H:i:s')];
                        }
                    }else{
                        $tmp[]=[$user_id,$k,isset($pilihan[$k]) ? $pilihan[$k] : NULL ,isset($jelaskan[$k]) ? $jelaskan[$k] : NULL,date('Y-m-d H:i:s')];
                    }
                }
            }
            $db->createCommand()->batchInsert(UserKuisioner::tableName(),['u_id','k_id','uk_ceklis','uk_keterangan','uk_created_at'],$tmp)->execute();
            $db->createCommand()->update(User::tableName(), ['u_kuisioner2_finish_at' =>date('Y-m-d H:i:s')],['u_id'=>Yii::$app->user->identity->u_id])->execute();
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