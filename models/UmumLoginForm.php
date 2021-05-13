<?php
namespace app\models;
use Yii;
use yii\base\Model;
class UmumLoginForm extends Model
{
    public $nik,$password,$tgl_lahir,$captcha;
    public $_user = false;
    public function rules()
    {
        return [
            [['nik','tgl_lahir','captcha'], 'required','message'=>'{attribute} harus diisi'],
            ['nik', 'validateLogin'],
            [['captcha'], 'captcha','captchaAction'=>'site/captcha'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'nik' => 'NIK/No. Rekam Medis',
            'tgl_lahir'=>'Tanggal Lahir',
        ];
    }
    public function validateNik($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getPeserta();
            if (!$user) {
                $this->addError($attribute, 'NIK tidak ditemukan atau pemeriksaan sudah selesai');
            }
        }
    }
    function validateLogin($attribute, $params)
    {
        if(!$this->hasErrors()){
            $daftar = UserDaftar::find()->select('ud_tgl_lahir')->where(['or',['ud_nik'=>trim($this->nik)],['ud_rm'=>trim($this->nik)]])->asArray()->limit(1)->one();
            if($daftar!=NULL){
                if($daftar['ud_tgl_lahir']!=date('Y-m-d',strtotime($this->tgl_lahir))){
                    $this->addError($attribute,'Akun tidak ditemukan, silahkan periksa kembali');
                }
            }else{
                $user=Pasien::find()->where(['or',['NO_PASIEN'=>trim($this->nik)],['NOIDENTITAS'=>trim($this->nik)]])->asArray()->limit(1)->one();
                if($user!=NULL){
                    if(date('Y-m-d',strtotime($user['TGL_LAHIR']))!=date('Y-m-d',strtotime($this->tgl_lahir))){
                        $this->addError($attribute,'Akun tidak ditemukan, silahkan periksa kembali');
                    }
                }else{
                    $this->addError($attribute,'Akun tidak ditemukan, silahkan periksa kembali');
                }
            }
        }
    }
    public function loginAsPeserta()
    {
        $login=Yii::$app->user->login($this->getPeserta());
        return $login;
    }
    public function getPeserta()
    {
        if ($this->_user === false){
            $this->_user = UserDaftar::findByUsername($this->nik,$this->tgl_lahir);
        }
        return $this->_user;
    }
    function getPasien()
    {
        $pasien= Pasien::find()->where(['or',['NO_PASIEN'=>$this->nik],['NOIDENTITAS'=>$this->nik]])->andWhere(['TGL_LAHIR'=>date('Y-m-d',strtotime($this->tgl_lahir))])->limit(1)->one();
        if($pasien!=NULL){
            return $pasien;
        }
        return false;
    }
    function loginAsPasien()
    {
        return Yii::$app->user->login($this->getPasien());
    }
    function saveLastLogin()
    {
        Yii::$app->db->createCommand()->update(User::tableName(), ['u_last_login' => date('Y-m-d H:i:s')],['u_id'=>$this->_user->u_id])->execute();
    }
}