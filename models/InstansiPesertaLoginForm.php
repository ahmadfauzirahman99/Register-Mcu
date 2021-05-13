<?php
namespace app\models;
use Yii;
use yii\base\Model;
class InstansiPesertaLoginForm extends Model
{
    public $nik,$tgl_lahir,$captcha;
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
            'nik' => 'NIK Peserta',
            'tgl_lahir'=>'Tanggal Lahir',
        ];
    }
    function validateLogin($attribute, $params)
    {
        if(!$this->hasErrors()){
            $data=User::find()->where(['u_nik'=>trim($this->nik),'u_tgl_lahir'=>date('Y-m-d',strtotime($this->tgl_lahir))])->asArray()->limit(1)->one();
            if($data==NULL){
                $this->addError($attribute, 'Data tidak ditemukan, pastikan data yang anda isi benar, hubungi HRD/Bagian Umum dari perusahaan/instansi anda untuk info lebih lanjut');
            }else{
                if(Yii::$app->params['validate_pi']){
                    if($data['u_approve_status']!='2'){
                        $this->addError($attribute,'Akun anda belum diverifikasi, silahkan hubungi HRD/Bagian Umum dari perusahaan/instansi anda untuk info lebih lanjut');
                    }
                }
            }
        }
    }
    public function getPeserta()
    {
        if ($this->_user === false){
            $this->_user = User::findPeserta($this->nik,$this->tgl_lahir);
        }
        return $this->_user;
    }
    function login()
    {
        return Yii::$app->user->login($this->getPeserta());
    }
}