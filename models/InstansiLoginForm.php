<?php
namespace app\models;
use Yii;
use yii\base\Model;
class InstansiLoginForm extends Model
{
    public $username;
    public $password;
    public $captcha;
    public $_user = false;
    public function rules()
    {
        return [
            [['username','password'], 'required','message'=>'{attribute} harus diisi'],
            ['password','validatePassword'],
            [['captcha'], 'captcha','captchaAction'=>'auth/captcha_login'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
        ];
    }
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()){
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Username atau password tidak cocok');
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
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}