<?php
namespace app\models;
use Yii;
use yii\base\Model;
class LoginForm extends Model
{
    public $nik;
    public $password;
    public $captcha;
    public $_user = false;
    public function rules()
    {
        return [
            [['nik'], 'required','message'=>'{attribute} harus diisi'],
            ['nik', 'validateNik'],
            [['captcha'], 'captcha','captchaAction'=>'site/captcha'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'nik' => 'ID/NIK Peserta',//No. Induk Kependudukan (NIK)'
        ];
    }
    public function validateNik($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, 'NIK tidak ditemukan atau pemeriksaan sudah selesai');
            }
        }
    }
    public function login()
    {
        return Yii::$app->user->login($this->getUser());
    }
    public function getUser()
    {
        if ($this->_user === false){
            $this->_user = User::findByUsername($this->nik);
        }
        return $this->_user;
    }
    function saveLastLogin()
    {
        Yii::$app->db->createCommand()->update(User::tableName(), ['u_last_login' => date('Y-m-d H:i:s')],['u_id'=>$this->_user->u_id])->execute();
    }
}