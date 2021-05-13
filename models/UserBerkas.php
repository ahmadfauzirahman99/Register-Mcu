<?php
namespace app\models;
use Yii;
class UserBerkas extends \yii\db\ActiveRecord
{
    public $file_ktp,$file_ijazah,$file_photo,$file_akta,$file_sertifikat,$file_surat_sehat,$file_str,$file_btcls,$file_rekom,$file_pernyataan,$file_anastesi,$file_makalah,$file_lamaran;
    public $tmp_file,$tmp_old_file,$filename,$jb,$msg;
    public static function tableName()
    {
        return 'user_berkas';
    }
    public function rules()
    {
        return [
            [['ub_user_id'], 'required'],
            [['ub_user_id','ub_berkas_id'], 'integer'],
            [['ub_berkas'],'string'],
            [['ub_created_at', 'ub_updated_at'], 'safe'],
            [['tmp_file'],'file','extensions'=>'jpg,jpeg,png','wrongExtension'=>'{attribute} harus berupa file {extensions}','maxSize'=>204800,'tooBig'=>'Ukuran file tidak boleh lebih dari 200 KiloByte (KB)','skipOnEmpty'=>true,'enableClientValidation'=>true],
        ];
    }
    public function attributeLabels()
    {
        return [
            'ub_id' => 'Ub ID',
            'ub_user_id' => 'Ub User ID',
            'ub_berkas_id' => 'Jenis Berkas',
            'ub_berkas' => 'Berkas Peserta',
            'tmp_file'=>'Berkas',
            'ub_created_at' => 'Ub Created At',
            'ub_updated_at' => 'Ub Updated At',
        ];
    }
    function getBerkas()
    {
        return $this->hasOne(Berkas::className(),['b_id'=>'ub_berkas_id']);
    }
    function getUser()
    {
        return $this->hasOne(User::className(),['u_id'=>'ub_user_id']);
    }
    function beforeSave($model)
    {
        if($this->isNewRecord){
            $this->ub_created_at=date('Y-m-d H:i:s');
        }
        return parent::beforeSave($model);
    }
    function saveBerkas($user=NULL)
    {
        if($user!=NULL){
            $id=$user['u_id'];
        }else{
            $id=Yii::$app->user->identity->u_id;
        }
        $this->ub_berkas=$this->ub_user_id.'-'.str_replace('.','',microtime(true)).'.'.strtolower($this->tmp_file->extension);
        if($this->validate()){
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $user = $db->createCommand()->update(User::tableName(), ['u_berkas_finish_at' =>date('Y-m-d H:i:s')],['u_id'=>$id])->execute();
                if($this->save(false)){
                    $this->deleteFile();
                    $this->tmp_file->saveAs(Yii::$app->params['storage'].$this->ub_berkas);
                }else{
                    $this->msg="Error 1,Berkas gagal diupload, refresh halaman dan silahkan coba lagi";
                    return false;
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
        }else{
            $this->msg=$this->errors;
            return false;
        }
    }
    function deleteFile()
    {
        if(!empty($this->tmp_old_file)){
            if(file_exists(Yii::$app->params['storage'].$this->tmp_old_file) && is_file(Yii::$app->params['storage'].$this->tmp_old_file)){
                unlink(Yii::$app->params['storage'].$this->tmp_old_file);
            }
        }
    }
}
