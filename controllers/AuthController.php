<?php
namespace app\controllers;
use Yii;
use app\widgets\App;
use app\models\User;
use app\models\InstansiLoginForm;
use app\models\InstansiPesertaLoginForm;
use app\models\AdminLoginForm;
use app\models\UmumLoginForm;
use app\models\UserDaftar;
use app\models\Informasi;
use app\models\Setting;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
class AuthController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','umum','umum-info','umum-login','umum-daftar','instansi','instansi-info','instansi-peserta-login','instansi-peserta-login-do','instansi-login','instansi-login-do','instansi-daftar','admin','admin-login','captcha_daftar','captcha_login','disclaimer'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                    [
                        'actions' => ['logout','accept'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action)
                {
                    $url=Yii::$app->urlManager->createUrl('/auth/index');
                    return $this->redirect($url);
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'=>['get'],
                    'umum'=>['get'],
                    'umum-info'=>['post'],
                    'umum-login'=>['post'],
                    'umum-daftar'=>['post'],

                    'instansi-info'=>['post'],
                    'instansi-info-petunjuk'=>['get'],
                    'instansi'=>['get'],
                    'instansi-peserta-login'=>['get'],
                    'instansi-peserta-login-do'=>['post'],
                    'instansi-login'=>['get'],
                    'instansi-login-do'=>['post'],
                    'instansi-daftar'=>['post'],
                    
                    'admin'=>['get'],
                    'admin-login'=>['post'],
                    'logout' => ['post','get'],

                    'disclaimer'=>['post'],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            'captcha_daftar' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'captcha_login' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    function actionIndex()
    {
        $this->layout="login_main";
        $setting=Yii::$app->db->createCommand("
            select 
                (select set_value from ".Setting::tableName()." where set_kode='front_info_instansi') as info_instansi,
                (select set_value from ".Setting::tableName()." where set_kode='front_info_umum') as info_umum
        ")->queryOne();
        return $this->render('index',[
            'setting'=>$setting
        ]);
    }
    function actionUmum()
    {
        if(!Yii::$app->user->isGuest){
            return $this->redirect(['peserta-umum/biodata']);
        }
        $this->layout="login_main";
        $login = new UmumLoginForm();
        $daftar = new UserDaftar;
        $daftar->scenario="umum_daftar";
        return $this->render('umum',[
            'login'=>$login,
            'daftar'=>$daftar,
        ]);
    }
    function actionUmumInfo()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $informasi=Informasi::find()->where(['i_status'=>'1','i_jenis'=>1])->orderBy(['i_urut'=>SORT_ASC])->asArray()->all();
            $file=Setting::find()->where(['set_kode'=>'pedoman_pemeriksaan_umum'])->asArray()->limit(1)->one();
            return $this->renderAjax('umum_info',[
                'informasi'=>$informasi,
                'file'=>$file,
            ]);
        }
    }
    function actionUmumLogin()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $model = new UmumLoginForm();
            $model->load($req->post());
            if($model->validate()){
                $daftar = UserDaftar::find()->select('ud_tgl_lahir')->where(['or',['ud_nik'=>trim($model->nik)],['ud_rm'=>trim($model->nik)]])->andWhere(['ud_tgl_lahir'=>date('Y-m-d',strtotime($model->tgl_lahir))])->asArray()->limit(1)->one();
                if($daftar!=NULL){
                    if($model->loginAsPeserta()){
                        $result=['status'=>true,'type'=>2];
                    }else{
                        $result=['status'=>false,'msg'=>$model->errors];
                    }
                }else{
                    if($model->loginAsPasien()){
                        $result=['status'=>true,'type'=>1];
                    }else{
                        $result=['status'=>false,'msg'=>'Data tidak ditemukan, silahkan periksa kembali'];
                    }
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionUmumDaftar()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $daftar = new UserDaftar;
            $daftar->scenario="umum_daftar";
            $daftar->load($req->post());
            if($daftar->validate()){
                if($daftar->save(false)){
                    $result=['status'=>true,'msg'=>'Anda berhasil mendaftar, silahkan login'];
                }else{
                    $result=['status'=>false,'msg'=>'Anda gagal mendaftar, silahkan periksa kembali'];
                }
            }else{
                $result=['status'=>false,'msg'=>$daftar->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionDisclaimer()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $informasi=Informasi::find()->where(['i_status'=>'1','i_jenis'=>2])->orderBy(['i_urut'=>SORT_ASC])->asArray()->all();
            return $this->renderAjax('disclaimer',[
                'informasi'=>$informasi
            ]);
        }
    }
    function actionInstansi()
    {
        $this->layout="login_main";
        return $this->render('instansi');
    }
    function actionInstansiInfo()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $informasi=Informasi::find()->where(['i_status'=>'1','i_jenis'=>3])->orderBy(['i_urut'=>SORT_ASC])->asArray()->all();
            $file=Setting::find()->where(['set_kode'=>'pedoman_pemeriksaan_instansi'])->asArray()->limit(1)->one();
            return $this->renderAjax('instansi_info',[
                'informasi'=>$informasi,
                'file'=>$file,
            ]);
        }
    }
    function actionInstansiPesertaLogin()
    {
        if(!Yii::$app->user->isGuest){
            if(App::isPesertaInstansi()){
                return $this->redirect(['/site/biodata']);
            }elseif(App::isPeserta() || App::isPasien()){
                return $this->redirect(['/peserta-umum/biodata']);
            }
        }
        $this->layout="login_main";
        $model = new InstansiPesertaLoginForm();
        return $this->render('instansi_peserta_login',[
            'model'=>$model,
        ]);
    }
    function actionInstansiPesertaLoginDo()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $model = new InstansiPesertaLoginForm();

        // var_dump($model);
        // exit;
            $model->load($req->post());
            if($model->validate()){
                if($model->login()){
                    $result=['status'=>true];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionInstansiLogin()
    {
        $this->layout="login_main";
        if (!Yii::$app->user->isGuest){
            if(App::isInstansi()){
                return $this->redirect(['instansi-informasi/list']);
            }elseif(App::isDokter()){
                return $this->redirect(['instansi-permintaan/index']);
            }
        }
        $login = new InstansiLoginForm();
        $daftar = new User;
        $daftar->scenario="create_akun_instansi";
        return $this->render('instansi_login',[
            'login'=>$login,
            'daftar'=>$daftar,
        ]);
    }
    function actionInstansiLoginDo()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $model = new InstansiLoginForm();
            $model->load($req->post());
            if($model->validate()){
                if($model->login()){
                    $result=['status'=>true];
                }else{
                    $result=['status'=>false,'msg'=>'Data tidak ditemukan'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionInstansiDaftar()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $daftar = new User;
            $daftar->scenario="create_akun_instansi";
            $daftar->load($req->post());
            $daftar->u_nik=$daftar->username;
            $daftar->u_password=Yii::$app->getSecurity()->generatePasswordHash($daftar->u_password);
            if($daftar->validate()){
                if($daftar->save(false)){
                    $result=['status'=>true,'msg'=>'Akun berhasil dibuat, silahkan login'];
                }else{
                    $result=['status'=>false,'msg'=>'Akun gagal dibuat, silahkan periksa data anda'];
                }
            }else{
                $result=['status'=>false,'msg'=>$daftar->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionAdmin()
    {
        if(!Yii::$app->user->isGuest){
            if(App::isDokter()){
                return $this->redirect(['/instansi-permintaan/index']);
            }elseif(App::isRm()){
                return $this->redirect(['/admin/peserta-list']);
            }
        }
        $this->layout="login_main";
        $model = new AdminLoginForm();
        return $this->render('admin_login',[
            'model'=>$model
        ]);
    }
    function actionAdminLogin()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $model = new AdminLoginForm();
            $model->load($req->post());
            if($model->validate()){
                if($model->login()){
                    $model->saveLastLogin();
                    $result=['status'=>true];
                }else{
                    $result=['status'=>false,'msg'=>'Login gagal dilakukan, periksa kembali akun anda'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionLogout()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            Yii::$app->user->logout();
            return $this->asJson(['status'=>true]);
        }
    }
    function actionDisclaimers()
    {
        $user=Yii::$app->user->identity;
        if($user->u_disclaimer_at!=NULL && $user->u_read_doc_at==NULL){
            return $this->redirect(['site/informasi']);
        }elseif($user->u_read_doc_at!=NULL && $user->u_disclaimer_at!=NULL){
            return $this->redirect(['site/biodata']);
        }
        $this->layout="login_main";
        $data=Informasi::find()->where(['i_jenis'=>2])->orderBy(['i_urut'=>SORT_ASC])->asArray()->all();
        return $this->render('disclaimer',[
            'data'=>$data,
        ]);
    }
    function actionAccept()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=Yii::$app->user->identity->u_id;
            Yii::$app->db->createCommand()->update(User::tableName(), ['u_disclaimer_at'=>date('Y-m-d H:i:s')],['u_id'=>$id])->execute();
            return $this->asJson(['status'=>true]);
        }
    }
}