<?php
namespace app\controllers;
use Yii;
use app\widgets\App;
use app\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
class InstansiProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','update'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isInstansi()){
                                return true;    
                            }
                            return false;
                        }
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
                    'index' => ['get'],
                    'update' => ['post'],
                ],
            ],
        ];
    }
    function actionIndex()
    {
        $model = User::findOne(['u_id'=>Yii::$app->user->identity->u_id]);
        $model->scenario="update_akun_instansi";
        $model->username=$model->u_nik;
        $model->u_password="";
        return $this->render('index',[
            'model'=>$model,
        ]);
    }
    function actionUpdate()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $model = User::findOne(['u_id'=>Yii::$app->user->identity->u_id]);
            $oldpass=$model->u_password;
            $model->scenario="update_akun_instansi";
            $model->load($req->post());
            if(!empty($model->u_password)){
                $model->u_password=Yii::$app->getSecurity()->generatePasswordHash($model->u_password);
            }else{
                $model->u_password=$oldpass;
            }
            if($model->validate()){
                if($model->save(false)){
                    $result=['status'=>true,'msg'=>'Akun berhasil diupdate'];
                }else{
                    $result=['status'=>false,'msg'=>'Akun gagal diupdate'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
}