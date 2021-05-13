<?php
namespace app\controllers;
use Yii;
use app\widgets\App;
use app\models\Pekerjaan;
use app\models\Informasi;
use app\models\Paket;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
class InstansiInformasiController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['list'],
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
                    'list' => ['get'],
                ],
            ],
        ];
    }
    function actionList()
    {
        $info = Informasi::find()->where(['i_jenis'=>3])->asArray()->all();
        $pekerjaan=Pekerjaan::all();
        $paket=Paket::find()->where(['is_active'=>1,'jenis_paket'=>2])->asArray()->all();
        return $this->render('list',[
            'info'=>$info,
            'pekerjaan'=>$pekerjaan,
            'paket'=>$paket,
        ]);
    }
    function actionUpdate()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $model = User::findOne(['u_id'=>Yii::$app->user->identity->u_id]);
            $model->scenario="update_akun_instansi";
            $model->load($req->post());
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