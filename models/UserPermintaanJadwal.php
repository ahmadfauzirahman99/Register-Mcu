<?php
namespace app\models;
use Yii;
class UserPermintaanJadwal extends \yii\db\ActiveRecord
{
    public $auto_set_jadwal;
    public static function tableName()
    {
        return 'user_permintaan_jadwal';
    }
    public function rules()
    {
        return [
            [['upj_up_id', 'upj_kuota','auto_set_jadwal'], 'required','on'=>'create_by_dokter','message'=>'{attribute} harus diisi'],
            [['upj_up_id', 'upj_kuota'], 'required','on'=>'update_by_dokter','message'=>'{attribute} harus diisi'],
            [['upj_up_id', 'upj_kuota','auto_set_jadwal'], 'integer'],
            [['upj_tgl'], 'safe'],
            [['upj_up_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserPermintaan::className(), 'targetAttribute' => ['upj_up_id' => 'up_id']],
        ];
    }
    public function attributeLabels()
    {
        return [
            'upj_id' => 'ID',
            'upj_up_id' => 'Permintaan Instansi',
            'upj_tgl' => 'Tanggal',
            'upj_kuota' => 'Kuota Peserta Perhari',
            'auto_set_jadwal'=>'Setting Jadwal',
        ];
    }
    function beforeSave($model)
    {
        $this->upj_tgl=date('Y-m-d',strtotime($this->upj_tgl));
        return parent::beforeSave($model);
    }
    function getUser()
    {
        return $this->hasMany(User::className(),['u_upj_id'=>'upj_id']);
    }
    public function getPermintaan()
    {
        return $this->hasOne(UserPermintaan::className(), ['up_id' => 'upj_up_id']);
    }
    static function all($up)
    {
        $data=self::find()->where(['upj_up_id'=>$up])->asArray()->all();
        return array_map(function($q){
            return ['id'=>$q['upj_id'],'tgl'=>date('d-m-Y',strtotime($q['upj_tgl']))];
        },$data);
    }
    static function getAllThisyear()
    {
        return self::find()->select('upj_tgl')->where('YEAR(upj_tgl) = :year',[':year'=>date('Y')])->asArray()->all();
    }
    function saveJadwal()
    {
        $transaction = self::getDb()->beginTransaction();
        try {
            if($this->isNewRecord){
                if($this->auto_set_jadwal==1){
                    $permintaan=UserPermintaan::find()->where(['up_id'=>$this->upj_up_id])->asArray()->limit(1)->one();
                    $time1=strtotime($permintaan['up_tgl_mulai']);
                    $time2=strtotime($permintaan['up_tgl_selesai']);
                    if($time2>$time1){
                        for($i=$time1; $i<=$time2; $i=$i+(60*60*24)){
                            if(!in_array(date('D',$i),["Sat","Sun"])){ //hari sabtu n minggu d skip
                                $count=UserPermintaanJadwal::find()->where(['upj_up_id'=>$this->upj_up_id,'upj_tgl'=>date('Y-m-d',$i)])->count();
                                if($count<1){
                                    $m = new UserPermintaanJadwal();
                                    $m->upj_up_id=$this->upj_up_id;
                                    $m->upj_kuota=$this->upj_kuota;
                                    $m->upj_tgl=date('Y-m-d',$i);
                                    $m->save(false);
                                }
                            }
                        }
                    }elseif($time1==$time2){
                        $this->save(false);
                    }else{
                        return false;
                    }                
                }elseif($this->auto_set_jadwal==2){
                    $this->save(false);
                }
            }else{
                $this->save(false);
            }
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
